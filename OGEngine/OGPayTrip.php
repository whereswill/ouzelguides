<?php
 
/**
 * PayTrip class
 *
 * This is a class to deal with paying trips
 *
 * @author Will Sharp <whereswill@bendcable.com>
 * @copyright 2014 Back Alley
 */
class PayTrip
{
	
	/**
     * @var Guide ID of Pay Trip instance
     */
	public $guide_id;
	/**
     * @var ID of Trip instance
     */
	private $trip;
	/**
     * @var Instance of ASDatabase class
     */
    private $db = null;

	/**
     * @var static pay rates that aren't set in UI
     */
	private $ump_bump;
	private $pay_two_guide;
	private $pay_sat;
	private $pay_shop;
	private $sat_pay_shop;
	private $max_half_day;
	private $max_daily_double;
	
	function __construct(Trip $trip, $inst_guide_id) { 
		          
		$this->guide_id = $inst_guide_id;
		$this->trip = $trip; 
		
    $this->db = ASDatabase::getInstance(); 

		$this->ump_bump = 10;
		$this->pay_two_guide = 5;
		$this->pay_sat = 20;
		$this->pay_shop = 15;
		$this->sat_pay_shop = 20;
		$this->max_half_day = 80.00;
		$this->max_daily_double = 40.00;
				          
	}

	public function set_guide_id($new_guideId) { 
		
		$this->guide_id = $new_guideId;
		  
	}
	
	public function get_guide_id() {

		return $this->guide_id;
		
	}
	
	/**
	 * Get base pay for a single guide for a trip
	 * @return dollar amount
	 */
	function getBasePay() {

		$obj_id = $this->guide_id;
		$trip_id = $this->trip->get_trip_id();
 		
		//get base rate
		$query = "SELECT pay_rates.rate 
						  FROM `pay_rates` 
						  JOIN `guide_payrates`
						  ON guide_payrates.payrate_id_fk = pay_rates.payrate_id
						  WHERE guide_payrates.user_id_fk = :user_id_fk
						  ORDER BY `created_on` DESC";

	  $g_rate = $this->db->select($query, array( 'user_id_fk' => $obj_id));
		if (count($g_rate) == 0) {
			return 0;
		} else {
			$g_rate = $g_rate[0];
		}

		//echo $g_rate['rate'] . "<br />";
		
		//get days
		$days = $this->trip->numDays();
		//echo $days;
		
		//return product
		if ($days === "1/2") {
			if ($this->trip->isDailyD() && $this->isOnHalfDay()) {
				return $this->max_daily_double;
			} elseif ($g_rate['rate'] >= $this->max_half_day) {
				return $this->max_half_day;
			} else {
				return $g_rate['rate'];
			}
		} else {
			return $g_rate['rate']*$days;
		}
		
	}

	/**
	 * Determine if a guide is on a half day on the same day as a daily double
	 * @return role_id or false
	 */
	function isOnHalfDay() {

		$obj_id = $this->guide_id;
		$trip_id = $this->trip->get_trip_id();

		//get put-in date for Daily Double
		$pi_date_query = "SELECT `putin_date`
						          FROM `trips`
						          WHERE `trip_id` = :trip_id
						          LIMIT 1";
		$pi_date = $this->db->select($pi_date_query, array( 'trip_id' => $trip_id));
		$pi_date = $pi_date[0]['putin_date'];

		//get 1/2 day trips on a given date for a given guide
		$trips_on_date = "SELECT trips.trip_types_fk, guide_events.role_id_fk
										  FROM `trips` 
										  JOIN `guide_events`
										  ON trips.trip_id = guide_events.trip_id_fk
										  WHERE guide_events.user_id_fk = :user_id_fk
										  AND trips.trip_id <> :trip_id
										  AND trips.putin_date = :pi_date";

	  $half_day = $this->db->select($trips_on_date, array( 'user_id_fk' => $obj_id, 'trip_id' => $trip_id, 'pi_date' => $pi_date));
		if (count($half_day) > 0) {
			$trip_types_fk = $half_day[0]['trip_types_fk'];
			if ($half_day[0]['trip_types_fk'] = 9) {
				return $half_day[0]['role_id_fk'];
			} else {
				return false;
			}
		} else {
			return false;
		}
		
	}

	/**
	 * Get TL pay for a single TL for a trip
	 * @return unformatted dollar amount
	 */
	function getTLPay() {

		if ($this->trip->isDailyD() && $this->isOnHalfDay() == 7) {
				return 0;
		} else {

			//get days and set boolean compare
			$trip_days = $this->trip->numDays();
			if ($trip_days == 1) {
				$day = 'Y';
			} else {
				$day = 'N';
			}

			//Get local or Sat.
			if ($this->trip->isSat() == true) {
				$sat = 'Y';
			} else {
				$sat = 'N';
			}

			//get TL rate
			$query = "SELECT `tl_amount`
			          FROM `tl_rates`
			          WHERE `day_bool` = :day AND `satellite_bool` = :sat";

		  $rate = $this->db->select($query, array( 'day' => $day, 'sat' => $sat));
		
			if (count($rate) == 0) {
				$rate = 0;
			} else {
				$rate = $rate[0];			
			}
			
			return $rate['tl_amount']*$trip_days;
		}
	}


	/**
	 * Get Rig pay for a single guide for a trip
	 * @return unformatted dollar amount
	 */
	function getRigPay() {

		$obj_id = $this->trip->trip_id;	
				
		//get number of riggers
		$rig_trip = 'SELECT `rigger_bool`
				  FROM `guide_events` 
				  WHERE `trip_id_fk` = :trip_id AND `rigger_bool` = "Y"';

	  $num_riggers = $this->db->select($rig_trip, array( 'trip_id' => $obj_id));
		$num_riggers = count($num_riggers);
		//echo $num_riggers;	
 		
		//get Rig rate
		$rig_rate = "SELECT trips.turnaround, river_trips.satellite 
				  FROM `trips` 
				  JOIN `river_trips`
				  ON trips.river_trips_fk = river_trips.rivertrip_id
				  WHERE trips.trip_id = :trip_id";

	  $rig_stats = $this->db->select($rig_rate, array( 'trip_id' => $obj_id));
		$rig_stats = $rig_stats[0];
		$rig = $rig_stats['turnaround'];
		$sat = $rig_stats['satellite'];

		$rigger_rate = "SELECT `rig_amount`
				  FROM `rig_rates` 
				  WHERE `satellite_bool` = :sat AND `turnaround_bool` = :rig";

	  $trip_rig = $this->db->select($rigger_rate, array( 'sat' => $sat, 'rig' => $rig,));
		$trip_rig = $trip_rig[0];
		
		//return product
		return $trip_rig['rig_amount']/$num_riggers;
	}

	/**
	 * Get Shop pay for a single guide for a trip
	 * @return unformatted dollar amount
	 */
	function getShopPay() {

		$obj_id = $this->trip->trip_id;
				
		//get number of shoppers
		$shop_trip = 'SELECT `food_shopper_bool`
				  FROM `guide_events` 
				  WHERE `trip_id_fk` = :trip_id AND `food_shopper_bool` = "Y"';

	  $num_shoppers = $this->db->select($shop_trip, array( 'trip_id' => $obj_id));
		$num_shoppers = count($num_shoppers);
		//echo $num_riggers;	
 		
		//get Shop rate
		if ($this->trip->isSat()) {
			$shop_rate = $this->trip->numDays()*$this->sat_pay_shop;
		} else {
			$shop_rate = $this->trip->numDays()*$this->pay_shop;
		}
		
		//return product
		return $shop_rate/$num_shoppers;
	}

	/**
	 * Get Sat pay for a trip
	 * @return unformatted dollar amount
	 */
	function getSatPay() {	
		
		//get days
		$days = $this->trip->numDays();
		//echo $days;
		
		//return product
		return $this->pay_sat*$days;
	}

	/**
	 * Get Bumps for a trip for a guide
	 * @return unformatted dollar amount
	 */
	function getBumpPay() {

		$bump_pay = array( 
          'pay' => 0,
          'two_guide' => false,
          'ump_bump' => false
        );

		//2-guide pay for Satellite Multi-day trips
		$two_guide = $this->getTwoGuide();
		if ($two_guide > 0) {
			$bump_pay['pay'] += $two_guide;
			$bump_pay['two_guide'] = true;
		}
		
		//Bump Pay for 1-day trips up and back
		$ump_bump = $this->getUmpBump();
		if ($ump_bump > 0) {
			$bump_pay['pay'] += $ump_bump;
			$bump_pay['ump_bump'] = true;
		}
		
		return $bump_pay;
	}

	/**
	 * Get Two-guide pay for a multi-day
	 * @return unformatted dollar amount
	 */
	function getTwoGuide() {

		//get days
		$days = $this->trip->numDays();
		//echo $days;
		
		//get num guides
		$guides = $this->trip->numberOfGuidesSwampers();
		//echo $days;
		
		if($this->trip->isSat() && $guides == 2) {
			return $this->pay_two_guide*$days;
		} else {
			return 0;
		}
	}

	/**
	 * Get Solo pay for a trip REMOVED FROM APP ON 6/25/16
	 * @return unformatted dollar amount
	 */
	// function getSoloPay() {

	// 	//get days
	// 	$days = $this->trip->numDays();
	// 	//echo $days;
		
	// 	//get num guides
	// 	$guides = $this->trip->numberOfGuidesSwampers();
	// 	//echo $days;
		
	// 	if($this->trip->isSat() && $guides == 2) {
	// 		return $this->pay_two_guide*$days;
	// 	} elseif($guides == 1){
	// 		return $this->pay_solo*$days;
	// 	} else {
	// 		return 0;
	// 	}
	// }

	/**
	 * Determine if a guide is on the Ump at all on either side of an Ump1D
	 * @return $ump_bump if false
	 */
	function getUmpBump() {

		$obj_id = $this->guide_id;
		$trip_id = $this->trip->get_trip_id();

		if ($this->trip->numDays() != 1 || $this->trip->getDrainage() != 'Umpqua') {
			return 0;
		} else {

			//get put-in date for Ump1D
			$pi_date_query = "SELECT `putin_date`
							          FROM `trips`
							          WHERE `trip_id` = :trip_id
							          LIMIT 1";
			$pi_date = $this->db->select($pi_date_query, array( 'trip_id' => $trip_id));
			$pi_date = $pi_date[0]['putin_date'];

		}

		//find Ump1D trips on a given date for a given guide
		$trips_on_date = "SELECT trips.trip_id
										  FROM `trips` 
										  JOIN `guide_events` ON trips.trip_id = guide_events.trip_id_fk
										  INNER JOIN `river_trips` ON trips.river_trips_fk = river_trips.rivertrip_id
										  WHERE guide_events.user_id_fk = :user_id_fk
										  AND trips.trip_id <> :trip_id
										  AND river_trips.drainage = 'Umpqua'
										  AND trips.putin_date IN (:pi_date, DATE_SUB(:pi_date, INTERVAL 1 DAY), DATE_ADD(:pi_date, INTERVAL 1 DAY))";

	  $ump_trips = $this->db->select($trips_on_date, array( 'user_id_fk' => $obj_id, 'trip_id' => $trip_id, 'pi_date' => $pi_date));

		//find other events on the Ump on a given date for a given guide
		$events_on_date = "SELECT trips.trip_id
										  FROM `trips` 
										  JOIN `other_events` ON trips.trip_id = other_events.trip_id_fk
										  INNER JOIN `river_trips` ON trips.river_trips_fk = river_trips.rivertrip_id
										  WHERE other_events.user_id_fk = :user_id_fk
										  AND trips.trip_id <> :trip_id
										  AND river_trips.drainage = 'Umpqua'
										  AND trips.putin_date IN (:pi_date, DATE_SUB(:pi_date, INTERVAL 1 DAY), DATE_ADD(:pi_date, INTERVAL 1 DAY))";

	  $ump_events = $this->db->select($events_on_date, array( 'user_id_fk' => $obj_id, 'trip_id' => $trip_id, 'pi_date' => $pi_date));

		if (count($ump_trips) > 0 || count($ump_events) > 0) {

			return 0;

		} else {

			//find Ump2D trips on a given date for a given guide
			$trips_on_date = "SELECT trips.trip_id
											  FROM `trips` 
											  JOIN `guide_events` ON trips.trip_id = guide_events.trip_id_fk
											  INNER JOIN `river_trips` ON trips.river_trips_fk = river_trips.rivertrip_id
											  WHERE guide_events.user_id_fk = :user_id_fk
											  AND trips.trip_id <> :trip_id
											  AND river_trips.drainage = 'Umpqua'
											  AND trips.putin_date IN (DATE_SUB(:pi_date, INTERVAL 2 DAY))";

		  $ump_trips = $this->db->select($trips_on_date, array( 'user_id_fk' => $obj_id, 'trip_id' => $trip_id, 'pi_date' => $pi_date));

		  if (count($ump_trips) == 0) {
		  	return $this->ump_bump;
		  } else {
		  	$umpTrip = new Trip;
		  	foreach ($ump_trips as $value) {
		  		$umpTrip->set_trip_id($value['trip_id']);
		  		if ($umpTrip->numDays() == 2) {
		  			return 0;
		  		}
		  	} // end foreach
		  	return $this->ump_bump;
		  } // end if count 2 days

		} //end if count 1 days
		
	}

  /**
   * Get bonus that guide is entitled to for this trip
   * @return unformatted amount.
   */
  function getBonusPay() {

  	if ($this->trip->isDailyD() && $this->isOnHalfDay()) {
				return 0;
		} else {

			//instantiate guide
			$guide = new Guide();
			$guide->set_guide_id($this->guide_id);

			//Get number of days
			$days = $this->trip->numDays();

			$bonus = $guide->getBonusPerDay();
				
			return $bonus*$days;
		}
	}
	
	function getCertPay() {

		if ($this->trip->isDailyD() && $this->isOnHalfDay()) {
				return 0;
		} else {

			$obj_id = $this->guide_id;
		
			//Get number of days
			$days = $this->trip->numDays();
			
			//Get all certs
			$sql = "SELECT guide_certs.exp_date, cert_rates.cert_amount, cert_rates.cert_type
					FROM `guide_certs` JOIN `cert_rates` 
					ON guide_certs.certrate_id_fk = cert_rates.certrate_id 
					WHERE guide_certs.user_id_fk = :user_id_fk";
			$cert = $this->db->select($sql, array( "user_id_fk" => $obj_id ));
			
			$cert_pay = 0;
			$cpr_bump = 0;
			$fa_bump = 0;
			
			foreach ($cert as $y) {
				if(new DateTime() < new DateTime($y['exp_date'])){
					switch ($y['cert_type']) {
						case "cpr":
							if ($y['cert_amount'] > $cpr_bump) {
								$cpr_bump = $y['cert_amount'];
							}
							break;
						case "fa":
							if ($y['cert_amount'] > $fa_bump) {
								$fa_bump = $y['cert_amount'];
							}
							break;						
						default:
							$cert_pay = $cert_pay + $y['cert_amount'];
							break;
					} // closing switch
				} // closing iff
			} // closing for each
			$cert_pay = $cert_pay + $fa_bump + $cpr_bump;
			return $cert_pay * $days;
		} // closing if
	} // closing function

} //closing class

?>