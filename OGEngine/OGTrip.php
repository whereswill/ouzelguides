<?php
 
/**
 * Trip class
 *
 * This is a class to deal with Trips
 *
 * @author Will Sharp <whereswill@bendcable.com>
 * @copyright 2014 Back Alley
 */
class Trip
{

	/**
    * @var ID of Trip instance
    */
	public $trip_id;
	
	/**
    * @var Instance of ASDatabase class
    */
   private $db = null;

  /**
	  * @var static to configure which date (pi or to) to compare to pay period end date
	  */
		private $trip_pay_date;
	
	function __construct() { 
		
    $this->db = ASDatabase::getInstance(); 

		$this->trip_pay_date = 'putin_date';
		          
	}

	public function set_trip_id($new_tripId) { 
		
		$this->trip_id = $new_tripId;
		  
	}
	
	public function get_trip_id() {

		return $this->trip_id;
		
	}
	
	/**
   * Get all details for a trip
   * @return array of details
   */
	public function getTripDetails() {
		
		$obj_id = $this->trip_id;
		
		$result = $this->db->select("SELECT * FROM `trips` WHERE `trip_id` = :trip_id LIMIT 1", array( "trip_id" => $obj_id ));
		return $result[0];
		
	}

	/**
   * Get all name for a trip given id
   * @return name
   */
	public function getTripNameID($rivertrip_id) {
		
		$result = $this->db->select("SELECT `rivertrip_name` FROM `river_trips` WHERE `rivertrip_id` = :id LIMIT 1", array( "id" => $rivertrip_id ));
		return $result[0]['rivertrip_name'];
		
	}

	/**
   * Get all details for a trip
   * @return array of details
   */
	public function getTripEventDate() {
		
		$obj_id = $this->trip_id;
		
		$event_date = $this->trip_pay_date;

    $query = "SELECT $event_date
		  				FROM `trips` 
		  				WHERE `trip_id` = :trip_id";

    $result = $this->db->select($query, array('trip_id' => $obj_id));

		$result = $result[0]['putin_date'];

		return $result;
		
	}
	
	/**
   * Get trip name for a trip
   * @return the trip name
   */
	public function getTripName() {
		
		$obj_id = $this->trip_id;

    $query = "SELECT river_trips.rivertrip_name
              FROM `river_trips`
							JOIN `trips` ON river_trips.rivertrip_id = trips.river_trips_fk
              WHERE trips.trip_id = :trip_id
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		$result = $result[0];
		
		if (count($result) == 1){
			return $result;
		} else {
			return "Error";
		}
		
	}

	/**
   * determine if the requested guide is on a trip
   * @return true or false
   */
	public function isGuideOnTrip($visitor_id) {
		
		$obj_id = $this->trip_id;

    $query = "SELECT `user_id_fk`
              FROM `guide_events`
              WHERE `trip_id_fk` = :trip_id
							AND `user_id_fk` = :user_id_fk";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id, 'user_id_fk' => $visitor_id));
		
		if (count($result) > 0){
			return true;
		} else {
			$query = "SELECT `user_id_fk`
	              FROM `other_events`
	              WHERE `trip_id_fk` = :trip_id
								AND `user_id_fk` = :user_id_fk";

	    $result2 = $this->db->select($query, array( 'trip_id' => $obj_id, 'user_id_fk' => $visitor_id));
			if (count($result2) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
	}

	/**
     * Get putin and takeout dates for a trip
     * @return the formatted dates
     */
	public function getTripDates() {
		
		$obj_id = $this->trip_id;

    $query = "SELECT `putin_date`, `takeout_date`
              FROM `trips`
              WHERE `trip_id` = :trip_id
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		
		if (count($result) == 1){
			return format_short_date($result[0]['putin_date']) . " - " . format_date($result[0]['takeout_date']);
		} else {
			return "Error";
		}
		
	}

	/**
	 * Get all user_id assigned to a paid trip
	 * @return array user_ids
	 */
	public function getAllAssignedToPaidTrip() {
		
		$obj_id = $this->trip_id;

    $query = "SELECT DISTINCT `user_id_fk`
              FROM `approvals`
              WHERE `trip_id_fk` = :trip_id";

    $result = $this->db->select($query, array('trip_id' => $obj_id));
		
		if (count($result) > 0){
			return $result;
		} else {
			return FALSE;
		}
		
	}

	/**
   * Get total pay for guides and associated work including bonuses for a trip
   * @return the total pay amount
   */
	public function getTripPay() {
		
		$obj_id = $this->trip_id;

		$guides = $this->getAllAssignedToPaidTrip();

    $base_pay = 0;
    $tl_pay = 0;
    $sat_pay = 0;
    $bump_pay = 0;
    $rig_pay = 0;
    $shop_pay = 0;
    $other_pay = 0;
    $assoc_pay = 0;
    $cert_pay = 0;

    $guide_tot = 0;
    $swamp_tot = 0;
    $assoc_tot = 0;
    $trip_total = 0;  

    $tot_guide_days = 0;

    foreach ($guides as $t) {

      $gtp = $this->getGuideTripPay($t['user_id_fk']);

      $this_guide_total = $gtp['base_pay'] +
			      							 $gtp['tl_pay'] +
			      							 $gtp['sat_pay'] +
			      							 $gtp['bump_pay'] +
			      							 $gtp['rig_pay'] +
			      							 $gtp['shop_pay'] +
			      							 $gtp['other_pay'] +
			      							 $gtp['cert_pay'];

			if ($gtp['swamp']) {
				$swamp_tot += $this_guide_total;
			} else {
				$guide_tot += $this_guide_total;
			}

			$assoc_tot += $gtp['assoc_pay'];

    }

    $trip_total = $guide_tot + $swamp_tot + $assoc_tot;

    $pay_stats = array(
      'guide_tot' 	=> $guide_tot,
      'swamp_tot' 	=> $swamp_tot,
      'assoc_tot' 	=> $assoc_tot,
      'trip_total' 	=> $trip_total
    );

    return $pay_stats;
		
	}

  /**
   * Return an array of pay breakdown for a trip for a given year
   * @param year, rivertrip_id
   * @return array breakdown of pay by river trip for season
   */
  public function getTripPayBreakdown() {
		
		$obj_id = $this->trip_id;

		$guides = $this->getAllAssignedToPaidTrip();

    $base_pay = 0;
    $tl_pay = 0;
    $sat_pay = 0;
    $bump_pay = 0;
    $rig_pay = 0;
    $shop_pay = 0;
    $other_pay = 0;
    $assoc_pay = 0;
    $cert_pay = 0;
    $bonus_pay = 0;

    $tot_guide_days = 0;

    foreach ($guides as $t) {

      $gtp = $this->getGuideTripPay($t['user_id_fk']);

      $base_pay += $gtp['base_pay'];
      $tl_pay += $gtp['tl_pay'];
      $sat_pay += $gtp['sat_pay'];
      $bump_pay += $gtp['bump_pay'];
      $rig_pay += $gtp['rig_pay'];
      $shop_pay += $gtp['shop_pay'];
      $other_pay += $gtp['other_pay'];
      $assoc_pay += $gtp['assoc_pay'];
      $cert_pay += $gtp['cert_pay'];
      $bonus_pay += $gtp['bonus_pay'];

      if ($gtp['guide']) {
        $guide_days = $this->riverDays($obj_id, $t['user_id_fk']);
        $tot_guide_days += $guide_days;
      }

    }

    $ytd_guide_total = $base_pay + $tl_pay + $sat_pay + $bump_pay + $rig_pay + $shop_pay + $other_pay + $cert_pay;

    $guide_trip_pay = array(
      'base_pay'  => $base_pay,
      'tl_pay'  => $tl_pay,
      'sat_pay'   => $sat_pay,
      'bump_pay'  => $bump_pay,
      'rig_pay'   => $rig_pay,
      'shop_pay'  => $shop_pay,
      'other_pay'   => $other_pay,
      'assoc_pay'   => $assoc_pay,
      'cert_pay'  => $cert_pay,
      'bonus_pay'   => $bonus_pay,
      'ytd_guide_total'   => $ytd_guide_total,
      'ytd_guide_days'  => $tot_guide_days
    );

    return $guide_trip_pay;
  }

  /**
   * Return an array of pay breakdown for a guide for a given trip
   * @param trip_id
   * @return array breakdown of pay for trip
   */
  public function getGuideTripPay($user_id) {

		$obj_id = $this->trip_id;

    $query = "SELECT *
              FROM `approvals`
              WHERE `trip_id_fk` = :trip_id
              AND `user_id_fk` = :user_id";

    $approvals = $this->db->select($query, array( 'trip_id' => $obj_id, 'user_id' => $user_id));
		
    $base_pay = 0;
		$tl_pay = 0;
		$sat_pay = 0;
		$bump_pay = 0;
		$rig_pay = 0;
		$shop_pay = 0;
		$other_pay = 0;
		$assoc_pay = 0;
		$cert_pay = 0;
		$bonus_pay = 0;

		$guide = FALSE;
		$swamp = FALSE;

    foreach ($approvals as $a) {
      $base_pay += $a['base_pay'];
			$tl_pay += $a['tl_pay'];
			$sat_pay += $a['sat_pay'];
			$bump_pay += $a['bump_pay'];
			$rig_pay += $a['rig_pay'];
			$shop_pay += $a['shop_pay'];
			if ($a['otherevent_id_fk']) {
				$assoc_pay += $a['other_pay'];
			} else {
				$other_pay += $a['other_pay'];
			}
			$cert_pay += $a['cert_pay'];
			$bonus_pay += $a['bonus_pay'];

			if ($a['guideevent_id_fk']) {
				$guide = TRUE;
			}

			if ($a['role_id_fk'] == 12) {
				$swamp = TRUE;
			}
    }

    $guide_trip_pay = array(
      'base_pay' 	=> $base_pay,
      'tl_pay' 	=> $tl_pay,
      'sat_pay' 	=> $sat_pay,
      'bump_pay' 	=> $bump_pay,
      'rig_pay' 	=> $rig_pay,
      'shop_pay' 	=> $shop_pay,
      'other_pay' 	=> $other_pay,
      'assoc_pay' 	=> $assoc_pay,
      'cert_pay' 	=> $cert_pay,
      'bonus_pay' 	=> $bonus_pay,
      'guide' => $guide,
      'swamp' => $swamp
    );

    return $guide_trip_pay;
  }
	
	/**
  * Get the put-in date for a trip
  * @return date in UNIX format
  */
	public function getSortDate() {
		
		$obj_id = $this->trip_id;
		
		$result = $this->db->select("SELECT UNIX_TIMESTAMP(`putin_date`) as sortdate FROM `trips` WHERE `trip_id` = :trip_id ORDER BY sortdate ASC", array( "trip_id" => $obj_id ));
		return $result[0]['sortdate'];
		
	}

	/**
     * Get putin and takeout dates for a trip
     * @return the formatted dates
     */
	public function getTripNameType() {
		
		$obj_id = $this->trip_id;

    $query = "SELECT `river_trips_fk`, `trip_types_fk`
              FROM `trips`
              WHERE `trip_id` = :trip_id
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		
		if (count($result) == 1){
				$name = $this->db->select("SELECT `rivertrip_name` FROM `river_trips` WHERE `rivertrip_id` = :river_trips_fk", array( "river_trips_fk" => $result[0]['river_trips_fk'] ));
				$name = $name[0];
				$type = $this->db->select("SELECT `triptype_name` FROM `trip_types` WHERE `triptype_id` = :trip_types_fk", array( "trip_types_fk" => $result[0]['trip_types_fk'] ));
				$type = $type[0];
		   	return $name['rivertrip_name'] . " " . $type['triptype_name'];
		} else {
			return "Error";
		}
		
	}

	/**
     * Get identifying stats for a trip that is not locked
     * @return the array of stats
     */
	public function getTripStats() {

		$obj_id = $this->trip_id;

    $query = "SELECT trips.trip_id, trips.putin_date, river_trips.rivertrip_name, trip_types.triptype_name
              FROM `trips`
							JOIN `river_trips` ON trips.river_trips_fk = river_trips.rivertrip_id
							JOIN `trip_types` ON trips.trip_types_fk = trip_types.triptype_id
              WHERE trips.trip_id = :trip_id
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		//print_r($result);
		
		if (count($result) > 0){
			$r = $result[0];
			if ($r['triptype_name'] == "1/2 Day") {
				$triptype_name = "Day";
			} else {
				$triptype_name = $r['triptype_name'];
			}
			$stats = htmlentities(format_short_date($r['putin_date']) . " " . $r['rivertrip_name'] . " " . $triptype_name);
			return $stats;
		} else {
			return "Error";
		}
		
	}

	/**
   * Get identifying stats for all trips that are not locked
   * @return the array of stats
   */
	public function getScheduledTrips() {

    $query = "SELECT trips.trip_id, trips.putin_date, river_trips.rivertrip_name, trip_types.triptype_name
              FROM `trips`
							JOIN `river_trips` ON trips.river_trips_fk = river_trips.rivertrip_id
							JOIN `trip_types` ON trips.trip_types_fk = trip_types.triptype_id
              WHERE trips.locked_on IS NULL
							ORDER BY trips.putin_date ASC";

    $result = $this->db->select($query);
		
		if (count($result) > 0){
			return $result;
		} else {
			return false;
		}
		
	}

	/**
   * Determine whether there is a regular half day scheduled on this day already
   * @return the trip_id of other trip or false
   */
	public function isAnotherHalfDay($putin_date, $river_trips_fk) {

    $query = "SELECT `trip_id`
              FROM `trips`
              WHERE `putin_date` = :putin_date
							AND `river_trips_fk` = :river_trips_fk";

    $result = $this->db->select($query, array( 'putin_date' => $putin_date, 'river_trips_fk' => $river_trips_fk));
		
		if (count($result) > 0){
			return $result;
		} else {
			return false;
		}
		
	}

	/**
	* Determine if there are unapproved trips
	* @return return number or false
	*/
	public function areUnapproved($end_date) {

    $query = "SELECT `trip_id`, `takeout_date`
              FROM `trips`
              WHERE `approved_on` IS NULL";

    $result = $this->db->select($query);

    $count = 0;
    foreach ($result as $key) {
    	if ($end_date > $key['takeout_date']) {
    		$count++;
    	}
    }
		
		if ($count > 0){
			return $count;
		} else {
			return false;
		}
		
	}
	
	/**
	* Determine which trips have the pu (or to) date occure on or before the end date
	* @return return number or false
	*/
	public function getApprovedTripsBeforeEnd($date) {

		$trip_date = $this->trip_pay_date;

    $query = "SELECT `trip_id`
		  				FROM `trips` 
		  				WHERE $trip_date <= :end_date
		  				AND approved_on IS NOT NULL
		  				AND locked_on IS NULL";

    $result = $this->db->select($query, array("end_date" => $date));

		return $result;
		
	}

	/**
	* Determine if trip hs the pu (or to) date occur on or before the end date
	* @return true or false
	*/
	public function isApprovedTripBeforeEnd($date) {

		$obj_id = $this->trip_id;

		$trip_date = $this->trip_pay_date;

    $query = "SELECT $trip_date
		  				FROM `trips` 
		  				WHERE `trip_id` = :trip_id
		  				LIMIT 1";

    $result = $this->db->select($query, array("trip_id" => $obj_id));

    if ($result[0][$trip_date] <= $date) {
    	return true;
    } else {
    	return false;
    }
		
	}

	/**
	 * Get trip type for a trip
	 * @return the type name
	 */
	public function isTripOnSheet() {
		
		$obj_id = $this->trip_id;

    $query = "SELECT `timesheet_id_fk`
              FROM `other_events`
              WHERE `trip_id_fk` = :trip_id
              AND `timesheet_id_fk` IS NOT NULL";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		
		if (count($result) > 0){
			return $result;
		} else {
			return FALSE;
		}
		
	}

	/**
     * if trip has a timesheet event associated, remove trip ID and put in notes
     * @return array of timesheet IDs or False if none
     */
	public function removeTripFromSheet() {
		
		$obj_id = $this->trip_id;

		$new_notes = $this->getTripStats();

      $query = "SELECT `otherevent_id`, `timesheet_id_fk`
                FROM `other_events`
                WHERE `trip_id_fk` = :trip_id
                AND `timesheet_id_fk` IS NOT NULL";

      $result = $this->db->select($query, array( 'trip_id' => $obj_id));

      foreach ($result as $key) {

      	$query = "SELECT `event_notes`
                  FROM `other_events`
                  WHERE `otherevent_id` = :otherevent_id
                  LIMIT 1";

      	$r = $this->db->select($query, array( 'otherevent_id' => $key['otherevent_id']));

      	if (count($r > 0)) {
      		$new_notes = $new_notes . " - " . $r[0]['event_notes'];
      	}

    		$ua_array = array(
					"trip_id_fk" => NULL,
				  "event_notes" => "$new_notes",
					);

				$t = $this->db->update("other_events", $ua_array, "otherevent_id = :otherevent_id", array( "otherevent_id" => $key['otherevent_id']));

      }
		
		if (count($result) > 0){
			return $result;
		} else {
			return FALSE;
		}
		
	}
	
	/**
   * Get trip type for a trip
   * @return the type name
   */
	public function getTripType() {
		
		$obj_id = $this->trip_id;

    $query = "SELECT trip_types.triptype_name
              FROM `trip_types`
							JOIN `trips` ON trip_types.triptype_id = trips.trip_types_fk
              WHERE trips.trip_id = :trip_id
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		$result = $result[0];
		
		if (count($result) == 1){
			return $result;
		} else {
			return "Error";
		}
		
	}
	
	/**
   * Get total number of shoppers guides, riggers and others assigned to a trip
   * @return total number.
   */
  function numberOfAssigned() {
	
		$obj_id = $this->trip_id;
		
    $query = "SELECT `user_id_fk`
              FROM `guide_events`
              WHERE `trip_id_fk` = :trip_id";

    $guide_result = $this->db->select($query, array( 'trip_id' => $obj_id));

    $query = "SELECT `user_id_fk`
              FROM `other_events`
              WHERE `trip_id_fk` = :trip_id
              AND `timesheet_id_fk` IS NULL";

    $other_result = $this->db->select($query, array( 'trip_id' => $obj_id));

		return count($guide_result) + count($other_result);
	}
	
	/**
   * Get number of guides currently assigned to a trip
   * @return number of guides
   */
  public function numberOfGuides() {
		
		$obj_id = $this->trip_id;
		
    $query = "SELECT `user_id_fk`
              FROM `guide_events`
              WHERE `trip_id_fk` = :trip_id
              AND `role_id_fk` <> 12";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));

		return count( $result );
	}
	
	/**
   * Get number of guides currently assigned to a trip
   * @return number of guides
   */
  public function numberOfGuidesSwampers() {
		
		$obj_id = $this->trip_id;
		
    $query = "SELECT `user_id_fk`
              FROM `guide_events`
              WHERE `trip_id_fk` = :trip_id";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));

		return count( $result );
	}
	
	/**
   * Get number of swampers currently assigned to a trip
   * @return number of swampers
   */
  public function numberOfSwampers() {
		
		$obj_id = $this->trip_id;
		
    $query = "SELECT `user_id_fk`
              FROM `guide_events`
              WHERE `trip_id_fk` = :trip_id
              AND `role_id_fk` = 12";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));

		return count( $result );
	}
	
	/**
   * Get number of guests from a trip
   * @return number of guides
   */
  public function numberOfGuests() {
		
		$obj_id = $this->trip_id;
		
    $query = "SELECT `guests_num`
              FROM `trips`
              WHERE `trip_id` = :trip_id
              LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));

		return $result[0]['guests_num'];
	}
	
	/**
   * Get number of others currently assigned to a trip
   * @return number of others.
   */
  function numberOfOthers() {

		$obj_id = $this->trip_id;
		
	  $query = "SELECT `user_id_fk`
	            FROM `other_events`
	            WHERE `trip_id_fk` = :trip_id";

	  $result = $this->db->select($query, array( 'trip_id' => $obj_id));

		return count( $result );
	}
	
	/**
   * Determine if a trip is Locked for editing (has been paid)
   * @return true or false.
   */
  public function isTripLocked() {

		$obj_id = $this->trip_id;

    $query = "SELECT `locked_on`
              FROM `trips`
              WHERE `trip_id` = :trip_id";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
	
		if ($result[0]['locked_on'] <> NULL) {
			return $result[0]['locked_on'];
		} else {
			return false;
		}
	}

	/**
	 * lock the trip and the approvals for all of the trip events
	 */
	function lockTrip($visitor_id, $lock_date) {	

		$obj_id = $this->trip_id;

		$lt_array = array(
			"locked_on" => "$lock_date",
			);
		$trip_lock = $this->db->update("trips", $lt_array, "trip_id = :trip_id", array( "trip_id" => $obj_id));

		$la_array = array(
			"locked" => "$lock_date",
			);
		$app_lock = $this->db->update("approvals", $la_array, "trip_id_fk = :trip_id AND timesheet_id_fk IS NULL", array( "trip_id" => $obj_id));

	}	
	
	/**
   * Determine if a trip is Approved but not Locked
   * @return true or false.
   */
  public function isTripApproved() {

		$obj_id = $this->trip_id;

    $query = "SELECT `locked_on`, `approved_on`
              FROM `trips`
              WHERE `trip_id` = :trip_id LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		
		if (count($result) == 1){
			$result = $result[0];
		} else {
			return false;
		}

		if ($result['locked_on']) {
			return false;
		} elseif ($result['approved_on']) {
			return true;
		} else {
			return false;
		}

	}

	/**
   * Determine if a trip is Approved
   * @return approved on
   */
  public function isApproved() {

		$obj_id = $this->trip_id;

    $query = "SELECT `approved_on`
              FROM `trips`
              WHERE `trip_id` = :trip_id LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		
		if (count($result) == 1){
			$result = $result[0];
			return $result['approved_on'];
		} else {
			return false;
		}

	}
	
	/**
   * Determine if a trip is a Satellite trip
   * @return true or false.
   */
  public function isSat() {

		$obj_id = $this->trip_id;

    $query = "SELECT river_trips.satellite
              FROM `river_trips`
							JOIN `trips` ON river_trips.rivertrip_id = trips.river_trips_fk
              WHERE trips.trip_id = :trip_id
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id));
		$result = $result[0];

		//echo $result['satellite'];

		if ($result['satellite'] == "Y") {
			return true;
		} else {
			return false;
		}

	}
	
	/**
   * Determine if a trip is a Day trip
   * @return true or false.
   */
  public function isDay() {

		if ($this->numDays() <= 1) {
			return true;
		} else {
			return false;
		}

	}
	
	/**
   * Get the integer number of days of a trip
   * @return the string "1/2" if its a 1/2 day or number of days if not.
   */
	public function numDays() {  

		$obj_id = $this->trip_id;

		if($this->isHalfDay()) {
			return "1/2";
		} else {
			$query = "SELECT `putin_date`, `takeout_date`
	              FROM `trips`
	              WHERE `trip_id` = :trip_id
								LIMIT 1";

	    $result = $this->db->select($query, array( 'trip_id' => $obj_id)); 
			$result = $result[0];
			
	    $val_1 = new DateTime($result['putin_date']);
	    $val_2 = new DateTime($result['takeout_date']);

	    $interval = $val_1->diff($val_2);

	    $day = $interval->d + 1;

			return $day;
		}
	    
	}
	
	/**
   * Get number of days of a trip
   * @return number of days and correct label.
   */
	public function tripDays() {   
	
		$day = $this->numDays();

	  $output   = '';

    if($day > 0){
      if ($day > 1){
        $output = $day." days ";       
      } else {
        $output = $day." day ";
      }
    }

    return $output;
	}

	/**
   * Get number of river days of a trip. Exclude Daily doubles if they worked the first one
   * @return number of days.
   */
	public function riverDays($trip_id, $user_id) {   
	
		$days = $this->numDays();
		if ($days == '1/2') {
			$daily_double = $this->isDailyD();
			if ($daily_double) {
				$trip = new PayTrip($this, $user_id);
				$second = $trip->isOnHalfDay();
				if ($second) {
					$river_days = 0;
				} else {
					$river_days = 1;
				}
			} else {
				$river_days = 1;
			}
		} else {
			$river_days = $days;
		}

    return $river_days;
	}
	
	/**
   * Determine whether a trip is a half-day trip
   * @return true if yes
   */
	public function isHalfDay() {   

		$obj_id = $this->trip_id;
		
		$query = "SELECT `trip_types_fk`
              FROM `trips`
              WHERE `trip_id` = :trip_id 
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id)); 
		$result = $result[0];

		if($result['trip_types_fk'] == 9 || $result['trip_types_fk'] == 11) {
			return true;
		} else {
			return false;
		}
	    
	}

	/**
   * Determine whether a trip is a daily double (second half-day) trip
   * @return true if yes
   */
	public function isDailyD() {   

		$obj_id = $this->trip_id;
		
		$query = "SELECT `trip_types_fk`
              FROM `trips`
              WHERE `trip_id` = :trip_id 
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id)); 
		$result = $result[0];

		if($result['trip_types_fk'] == 11) {
			return true;
		} else {
			return false;
		}
	    
	}
	
	/**
   * Get the drainage for a trip
   * @return the string
   */
	public function getDrainage() {  

		$obj_id = $this->trip_id;

    $query = "SELECT river_trips.drainage
              FROM `river_trips`
							JOIN `trips` ON river_trips.rivertrip_id = trips.river_trips_fk
              WHERE trips.trip_id = :trip_id
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id)); 
		$result = $result[0]['drainage'];

		return $result;
    
	}
	
	/**
   * Get the integer number of miles for a trip
   * @return the integer
   */
	public function getMiles() {  

		$obj_id = $this->trip_id;

    $query = "SELECT river_trips.mileage
              FROM `river_trips`
							JOIN `trips` ON river_trips.rivertrip_id = trips.river_trips_fk
              WHERE trips.trip_id = :trip_id
							LIMIT 1";

    $result = $this->db->select($query, array( 'trip_id' => $obj_id)); 
		$result = $result[0]['mileage'];

		return $result;
    
	}

}

?>