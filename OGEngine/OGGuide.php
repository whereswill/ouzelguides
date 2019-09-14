<?php
 
/**
 * Guide class
 *
 * This is a class to deal with Guides
 *
 * @author Will Sharp <whereswill@bendcable.com>
 * @copyright 2014 Back Alley
 */
class Guide extends User
{

	/**
     * @var ID of Trip instance
     */
	public $guide_id;
	
	/**
     * @var Instance of ASDatabase class
     */
    private $db = null;
	
	function __construct() { 
		
		// if ($inst_guide_id) {
		parent::__construct();
		// 	          
		// $this->guide_id = $inst_guide_id; 
		// }
			
	    $this->db = ASDatabase::getInstance(); 
			          
	}

	public function set_guide_id($new_guideId) { 
		
		$this->set_user_id($new_guideId);
		$this->guide_id = $new_guideId;
		  
	}
	
	public function get_guide_id() {

		return $this->guide_id;
		
	}

	/**
     * Get details for a given guide
     * @return details or false if not a guide
     */
    function getGuideDetails() {

		$obj_id = $this->guide_id;

    $query = "SELECT * 
    					FROM `guide_details`
		  				WHERE `user_id_fk` = :user_id_fk";

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id));

		if (count($result) > 0) {
			return $result[0];
		} else {
			return false;
		}
	}
	
  /**
   * Add guide details
   * @param array $updateData Associative array where keys are database fields that need
   * to be added and values are new values for provided database fields.
   */
  public function addGuide($data) {

    // insert guide info
    $err = $this->db->insert('guide_details',  array (
        'user_id_fk'      => $data['user_id'],
        'active_bool'     => $data['active_bool'],
        'seniority'      	=> $data['seniority'],
        'hire_date'     	=> $data['hire_date'],
        'bonus_eligible' 	=> $data['bonus_eligible'],
        'bonus_start' 		=> $data['bonus_start']
    ));

    if ($err) {
      echo json_encode(array(
        "status"  => "success",
        "msg"     => "Guide was added successfully"
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: Guide addition was not completed"
      ));
    }
  }

  /**
   * Updates guide details
   * @param array $updateData Associative array where keys are database fields that need
   * to be updated and values are new values for provided database fields.
   */
  public function updateGuide($data) {
    $err = $this->db->update(
        "guide_details", 
        $updateData = array(
	      'active_bool'     => $data['active_bool'],
	      'seniority'      	=> $data['seniority'],
	      'hire_date'     	=> $data['hire_date'],
	      'bonus_eligible' 	=> $data['bonus_eligible'],
	      'bonus_start' 		=> $data['bonus_start']
	    	), 
        "`user_id_fk` = :id",
        array( "id" => $this->guide_id )
    );

    if ($err) {
      echo json_encode(array(
        "status"  => "success",
        "msg"     => "Guide was updated successfully"
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: Guide update was not completed"
      ));
    }
  }

  /**
   * Updates guide details.
   * @param array $updateData Associative array where keys are database fields that need
   * to be updated and values are new values for provided database fields.
   */
  public function updateGuideDetails($updateData) {
    $this->db->update(
                "guide_details", 
                $updateData, 
                "`user_id_fk` = :id",
                array( "id" => $this->guide_id )
           );
  }

	/**
	 * Get bonus that guide is entitled to for this trip
	 * @return unformatted amount.
	 */
	function getBonusPerDay() {

		$obj_id = $this->guide_id;
		 
		$query = "SELECT `bonus_eligible`
	            FROM `guide_details`
	            WHERE `user_id_fk` = :user_id_fk";

	  $result = $this->db->select($query, array( 'user_id_fk' => $obj_id));
		$result = $result[0];

		//Calculate bonus if eligible
		if($result['bonus_eligible'] == "Y"){
			
			//Get number of seasons
			$years = $this->bonusYears();
			
			//Get bonus array
			$query = "SELECT `num_years`, `bonus_amount`
		            FROM `bonus_rates`
					  		ORDER BY `num_years` ASC";

		  $result = $this->db->select($query);
		
			//Find correct rate from array
			$bonus = 0;
			foreach ($result as $y) {
				if($years >= $y['num_years']) {
					$bonus = $y['bonus_amount'];
				}
			}
			
			return $bonus;
		} else{
			return '0';
		}
	}
	
	/**
  	* Get the current FA pay for a guide
  	* @return amount
  	*/
 	function getFAPay() {

		$obj_id = $this->guide_id;
		
		//Get all certs that are not other
		$sql = 'SELECT guide_certs.exp_date, cert_rates.cert_amount, cert_rates.cert_type
						FROM `guide_certs` JOIN `cert_rates` 
						ON guide_certs.certrate_id_fk = cert_rates.certrate_id 
						WHERE guide_certs.user_id_fk = :user_id_fk
				  	AND cert_rates.cert_type <> "other"';
		$cert = $this->db->select($sql, array( "user_id_fk" => $obj_id ));
		
		$cpr_bump = 0;
		$fa_bump = 0;
		$cert_pay = 0;
		
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
						$cert_pay = 0;
						break;
				} // closing switch
			} // closing if
		} // closing for each
		$cert_pay = $cert_pay + $fa_bump + $cpr_bump;
		return $cert_pay;
	} // closing function


	 //////////////////// CERTIFICATIONS ///////////////////////
	///////////////////////////////////////////////////////////


  /**
   * Inserts cert into database.
   * @param int $userId Id of user for whom cert is being added
   * @param int $certrate_id of selected cert
   * @return date of expiration
   */
  function addCert($cert_id, $exp_date) {

		$obj_id = $this->guide_id;
    // $user     = new ASUser($userId);
    // $userInfo = $user->getInfo();

    $this->db->insert("guide_certs",  array(
        "user_id_fk"  => $obj_id,
        "certrate_id_fk" => $cert_id,
        "exp_date"    => $exp_date
    ));

    $guidecert_id = $this->db->lastInsertId();

    $query = "SELECT `certrate_name`
	            FROM `cert_rates`
	            WHERE `certrate_id` = :cert_id";

	  $certname = $this->db->select($query, array( 'cert_id' => $cert_id));
		$certname = $certname[0]['certrate_name'];

    $result = array(
        "guidecert_id"  => $guidecert_id,
        "cert"      		=> $certname,
        "exp_date"  		=> format_date($exp_date),
        "current"  			=> true
    );
    return json_encode($result);
  }

   /**
   * Deletes cert from database.
   * @param int $guidecert_id Id of cert being deleted
   */
  function deleteCert($guidecert_id) {

		$this->db->delete("guide_certs", "guidecert_id = :guidecert_id", array( "guidecert_id" => $guidecert_id));

  }
	
	/**
  	* Get the bonuses for other certs
  	* @return amount
  	*/
	function getOtherCertPay() {
		
		$obj_id = $this->guide_id;

	  $today = new DateTime();

		//get all certs for guide
		$query = 'SELECT cert_rates.certrate_name, cert_rates.cert_amount
				  FROM `guide_certs` 
				  JOIN `cert_rates`
				  ON guide_certs.certrate_id_fk = cert_rates.certrate_id
				  WHERE guide_certs.user_id_fk = :user_id_fk
				  AND cert_rates.cert_type = "other"
				  AND guide_certs.exp_date >= CURDATE()
				  AND cert_rates.cert_amount != 0.00';

		$certs = $this->db->select($query, array( "user_id_fk" => $obj_id ));

		if (count($certs) > 0) {
			return $certs;
		} else {
			return false;
		}
		
 	}
	
	/**
  	* Compare both CPR and FA exp dates with todays date
  	* @return true if both certs are current
  	*/
	function areCertsCurrent() {
		
		$obj_id = $this->guide_id;

	  $today = new DateTime();

		//get all certs for guide
		$query = "SELECT guide_certs.exp_date, cert_rates.cert_type
				  FROM `guide_certs` 
				  JOIN `cert_rates`
				  ON guide_certs.certrate_id_fk = cert_rates.certrate_id
				  WHERE guide_certs.user_id_fk = :user_id_fk";

		$certs = $this->db->select($query, array( "user_id_fk" => $obj_id ));

		$currentFA = FALSE;
		$currentCPR = FALSE;
		$existsFA = FALSE;
		$existsCPR = FALSE;
		foreach ($certs as $cert){
			$expdate = new DateTime($cert['exp_date']);

			if ($cert['cert_type'] == "fa") {
				$existsFA = TRUE;
				if ($today < $expdate) {
					$currentFA = TRUE;
				}
			}
			if ($cert['cert_type'] == "cpr") {
				$existsCPR = TRUE;
				if ($today < $expdate) {
					$currentCPR = TRUE;
				}
			}
		}
		if ($existsFA == TRUE && $existsCPR == TRUE) {
			if ($currentFA == TRUE && $currentCPR == TRUE) {
				return TRUE;
			} else {
				return FALSE;			
			}
		} else {
			return FALSE;
		}
 	}

	/**
     * Compare both CPR and FA exp dates with take-out date
     * @return true if either cert will expire or one doesn't exist
     */
	function willCertExpire($trip_id) {

		$obj_id = $this->guide_id;

		//get put-in date
		$date = $this->db->select("SELECT `takeout_date` FROM `trips` WHERE `trip_id` = :trip_id", array( "trip_id" => $trip_id ));
		$date = $date[0];
		$todate = new DateTime($date['takeout_date']);

		//get all certs for guide
		$query = "SELECT guide_certs.exp_date, cert_rates.cert_type
				  FROM `guide_certs` 
				  JOIN `cert_rates`
				  ON guide_certs.certrate_id_fk = cert_rates.certrate_id
				  WHERE guide_certs.user_id_fk = :user_id_fk";

		$certs = $this->db->select($query, array( "user_id_fk" => $obj_id ));

		$willExpireFA = FALSE;
		$willExpireCPR = FALSE;
		$existsFA = FALSE;
		$existsCPR = FALSE;
		foreach ($certs as $cert){
			$expdate = new DateTime($cert['exp_date']);

			if ($cert['cert_type'] == "fa") {
				$existsFA = TRUE;
				if ($todate > $expdate) {
					$willExpireFA = TRUE;
				}
			}
			if ($cert['cert_type'] == "cpr") {
				$existsCPR = TRUE;
				if ($todate > $expdate) {
					$willExpireCPR = TRUE;
				}
			}
		}
		if ($existsFA == TRUE && $existsCPR == TRUE) {
			if ($willExpireFA == TRUE || $willExpireCPR == TRUE) {
				return TRUE;
			} else {
				return FALSE;			
			}
		} else {
			return TRUE;
		}
    }

	/**
     * Get number of seasons a guide has worked
     * @return number of seasons and correct label.
     */
	function guideYears() { 

	    $year = $this->numSeasons();

	    $output   = '';

	    if($year > 0){
	        if ($year == 1){
	            $output = "First year";       
	        } else if ($year == 2){
	            $output = "Second year";
	        } else if ($year == 3){
	            $output = "Third year";
	        } else if ($year == 4){
	            $output = "Fourth year";
	        } else if ($year == 5){
	            $output = "Fifth year";
	        } else {
		        $output = $year." years";
			}
	    }

	    return $output;
	}

	/**
     * Get number of seasons a guide has worked
     * @return unformatted number.
     */
	function numSeasons() {  

		$obj_id = $this->guide_id;

		$result = $this->db->select("SELECT `hire_date` FROM `guide_details` WHERE `user_id_fk` = :user_id_fk LIMIT 1", array( "user_id_fk" => $obj_id )); 

    $val_1 = new DateTime($result[0]['hire_date']);
    $val_2 = new DateTime();

    $interval = $val_1->diff($val_2);

    $year = $interval->y + 1;

    return $year;

	}

	/**
   * Get number of seasons a guide has been eligible for bonus
   * @return unformatted number.
   */
	function bonusYears() {  

		$obj_id = $this->guide_id;

		$result = $this->db->select("SELECT `bonus_start` FROM `guide_details` WHERE `user_id_fk` = :user_id_fk LIMIT 1", array( "user_id_fk" => $obj_id )); 

		if ($result[0]['bonus_start'] == 0) {
			return 0;
		} else {
	    $val_1 = new DateTime($result[0]['bonus_start']);
	    $val_2 = new DateTime();

	    $interval = $val_1->diff($val_2);

	    $year = $interval->y + 1;

	    return $year;
	  }
	}

    /**
     * Get total number of trips that guide is scheduled on. Does not include trips that are approved or locked.
     * @return total number.
     */
    function isOnAssigned() {

		$obj_id = $this->guide_id;

		$query = "SELECT guide_events.trip_id_fk 
				  FROM `guide_events`
				  LEFT OUTER JOIN `approvals`
				  ON guide_events.trip_id_fk = approvals.trip_id_fk
				  WHERE approvals.trip_id_fk IS NULL AND guide_events.user_id_fk = :user_id_fk";

        $guide_result = $this->db->select($query, array( 'user_id_fk' => $obj_id));

		$query = "SELECT other_events.trip_id_fk 
				  FROM `other_events`
				  LEFT OUTER JOIN `approvals`
				  ON other_events.trip_id_fk = approvals.trip_id_fk
				  WHERE approvals.trip_id_fk IS NULL AND other_events.user_id_fk = :user_id_fk";

        $other_result = $this->db->select($query, array( 'user_id_fk' => $obj_id));

		$assigned = count($guide_result) + count($other_result);

		if ($assigned == 0) {
			return false;
		} else {
			return $assigned;
		}
	}

    /**
     * Determine if a guide is currently scheduled on a trip or other event that has been approved
     * @return an array of approval_ids or false.
     */
    function isOnApproved() {

		$obj_id = $this->guide_id;

        $query = "SELECT `approval_id`, `trip_id_fk`, `timesheet_id_fk`
                    FROM `approvals`
                    WHERE `user_id_fk` = :user_id_fk AND `locked` IS NULL";

        $result = $this->db->select($query, array( 'user_id_fk' => $obj_id));

		//print_r($result['trip_id_fk']);

		if (count($result) == 0) {
			return false;
		} else {
			return $result;
		}

	}

    /**
     * Determine if a guide is currently scheduled on a trip or other event that has been locked
     * @return an array of approval_ids or false.
     */
    function isOnLocked() {

		$obj_id = $this->guide_id;

    $query = "SELECT `approval_id`, `trip_id_fk`, `timesheet_id_fk`
              FROM `approvals`
              WHERE `user_id_fk` = :user_id_fk AND `locked` IS NOT NULL";

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id));

		if (count($result) == 0) {
			return false;
		} else {
			return $result;
		}

	}


	 //////////////////// PAY RATES /////////////////////////
	////////////////////////////////////////////////////////

  /**
   * Inserts cert into database.
   * @param int $userId Id of user for whom cert is being added
   * @param int $certrate_id of selected cert
   * @return date of expiration
   */
  function addPayRate($payrate_id, $notes, $visitor_id) {

		$obj_id = $this->guide_id;

		$datetime = date("Y-m-d H:i:s");

    $this->db->insert("guide_payrates",  array(
        "user_id_fk"  	=> $obj_id,
        "payrate_id_fk" => $payrate_id,
        "notes"    			=> $notes,
        "created_by"		=> $visitor_id
    ));

    $guiderate_id = $this->db->lastInsertId();

    $query = "SELECT `rate`
	            FROM `pay_rates`
	            WHERE `payrate_id` = :payrate_id";

	  $rate = $this->db->select($query, array('payrate_id' => $payrate_id));
		$rate = $rate[0]['rate'];

    $result = array(
        "guiderate_id"  => $guiderate_id,
        "rate"      		=> $rate,
        "notes"      		=> $notes,
        "start_date"  	=> format_date($datetime)
    );

    return json_encode($result);
  }

  /**
   * Deletes cert from database.
   * @param int $guidecert_id Id of cert being added
   */
  function deletePayRate($guidepayrate_id) {

		$this->db->delete("guide_payrates", "guidepayrate_id = :guidepayrate_id", array( "guidepayrate_id" => $guidepayrate_id));

  }

  /**
   * Get a guides history of pay rates including the current pay rate
   * @return an array of pay rates and active dates, false if no pay rate.
   */
  function getGuidePayrates() {

		$obj_id = $this->guide_id;

		$payrate_sql = "SELECT guide_payrates.guidepayrate_id, guide_payrates.notes, guide_payrates.created_on, pay_rates.rate 
				FROM `guide_payrates` JOIN `pay_rates` 
				ON guide_payrates.payrate_id_fk = pay_rates.payrate_id 
				WHERE guide_payrates.user_id_fk = :user_id_fk
				ORDER BY guide_payrates.created_on DESC";

		$result = $this->db->select($payrate_sql, array( "user_id_fk" => $obj_id ));

		//print_r($result);

		if (count($result) == 0) {
			return false;
		} else {
			$c = 0;
			foreach($result as $r){
				if($c == 0){
					$result[0]['current'] = true;
				} else {
					$result[$c]['current'] = false;
				}
				$c++;
			}
			return $result;
		}
	}

	/**
     * Get a guides current pay rate
     * @return a rate, false if no pay rate.
     */
    function getCurrentPayrate() {

		//$obj_id = $this->guide_id;

		$current = $this->getGuidePayrates();

		if (count($current) == 0) {
			return false;
		} else {
			return $current[0]['rate'];
		}

	}

	/**
   * Get a guides current bonus amount for the current season
   * @return an amount, false if none.
   */
  function getCurrentBonus() {

		$obj_id = $this->guide_id;
		$year = date("Y");

    $query = "SELECT SUM(`bonus_pay`) as bonus
              FROM `approvals`
              WHERE `user_id_fk` = :user_id_fk 
              AND `locked` IS NOT NULL
              AND EXTRACT(YEAR FROM `event_date`) = :year";

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id, "year" => $year));

		return $result[0]['bonus'];

	}
	
	/**
   * Get a guides history of certifications
   * @return an array of certs and expiration dates, false if no certs.
   */
  function getGuideCerts() {

		$obj_id = $this->guide_id;

		$cert_sql = "SELECT guide_certs.guidecert_id, guide_certs.exp_date, cert_rates.certrate_name 
								 FROM `guide_certs` JOIN `cert_rates` 
								 ON guide_certs.certrate_id_fk = cert_rates.certrate_id 
								 WHERE guide_certs.user_id_fk = :user_id_fk
								 ORDER BY guide_certs.exp_date DESC";

		$result = $this->db->select($cert_sql, array( "user_id_fk" => $obj_id ));

		$now = new DateTime();

		if (count($result) == 0) {
			return false;
		} else {
			$c = 0;
			foreach($result as $r){
				$current = new DateTime($r['exp_date']);
				if($current > $now) {
					$result[$c]['current'] = true;
				} else {
					$result[$c]['current'] = false;
				}
				$c++;
			}
			return $result;
		}

	}
	
	/**
   * Get IDs for a guides set of skills
   * @return an array of rivertrips and details, false if none.
   */
  function getGuideSkillsID() {

		$obj_id = $this->guide_id;

		$sql = "SELECT `skill_id_fk`
						FROM `guide_skills`
						WHERE `user_id_fk` = :user_id_fk";

		$result = $this->db->select($sql, array( "user_id_fk" => $obj_id));
			
			if (count($result) > 0) {
				$guideSkills = [];
				foreach ($result as $r) {
					array_push($guideSkills, $r['skill_id_fk']);
				}
				return $guideSkills;
			} else {
				return false;
			}
	}
	
	/**
   * Get a guides river awareness
   * @return an array of rivertrips and details, false if none.
   */
  function getGuideSkills() {

  	$skills = new Skill();
  	$guideSkills = $skills->getSkills();

		$obj_id = $this->guide_id;

		$sql = "SELECT *
						FROM `guide_skills`
						WHERE `user_id_fk` = :user_id_fk
						AND `skill_id_fk` = :skill_id_fk";

		$c = 0;
		foreach ($guideSkills as $r) {

			$result = $this->db->select($sql, array( "user_id_fk" => $obj_id, "skill_id_fk" => $r['skill_id']));
			
			if (count($result) > 0) {
				$guideSkills[$c]['guideskill_id'] = $result[0]['guideskill_id'];
				$guideSkills[$c]['notes'] = $result[0]['notes'];
				$guideSkills[$c]['created_on'] = $result[0]['created_on'];
			} else {
				$guideSkills[$c]['guideskill_id'] = NULL;
				$guideSkills[$c]['notes'] = NULL;
				$guideSkills[$c]['created_on'] = NULL;
			}
			$c++;

		}
		
		return $guideSkills;

	}

  /**
   * Inserts guideskill into database.
   * @param int $skill_id Id of skill for whom skill is being added
   * @param string $notes of added notes
   * @return result
   */
  function addGuideSkill($skill_id, $notes) {

		$obj_id = $this->guide_id;

		$datetime = date("Y-m-d H:i:s");

    $this->db->insert("guide_skills",  array(
        "user_id_fk"  		=> $obj_id,
        "skill_id_fk" 		=> $skill_id,
        "notes"    				=> $notes
    ));

    $guideskill_id = $this->db->lastInsertId();

    $result = array(
        "guideskill_id"  => $guideskill_id,
        "notes"      		 => $notes,
        "start_date"  	 => format_date($datetime)
    );

    return json_encode($result);
  }

   /**
   * Deletes guideskill from database.
   * @param int $guideskill_id Id of line being deleted
   */
  function deleteGuideSkill($guideskill_id) {

		$this->db->delete("guide_skills", "guideskill_id = :guideskill_id", array( "guideskill_id" => $guideskill_id));

  }
	
	/**
   * Get IDs for a guides set of skills
   * @return an array of rivertrips and details, false if none.
   */
  function getGuideRiversID() {

		$obj_id = $this->guide_id;

		$sql = "SELECT rivertrip_id_fk
						FROM `guide_rivers`
						WHERE `user_id_fk` = :user_id_fk";

		$result = $this->db->select($sql, array( "user_id_fk" => $obj_id));
			
			if (count($result) > 0) {
				$guideRivers = [];
				foreach ($result as $r) {
					array_push($guideRivers, $r['rivertrip_id_fk']);
				}
				return $guideRivers;
			} else {
				return FALSE;
			}
	}
	
	/**
   * Get a guides river awareness
   * @return an array of rivertrips and details, false if none.
   */
  function getGuideRivers() {

  	$rivertrips = new RiverTrip();
  	$guideRivers = $rivertrips->getTrips();

		$obj_id = $this->guide_id;

		$sql = "SELECT *
						FROM `guide_rivers`
						WHERE `user_id_fk` = :user_id_fk
						AND `rivertrip_id_fk` = :rivertrip_id_fk";

		$c = 0;
		foreach ($guideRivers as $r) {

			$result = $this->db->select($sql, array( "user_id_fk" => $obj_id, "rivertrip_id_fk" => $r['rivertrip_id']));
			
			if (count($result) > 0) {
				$guideRivers[$c]['guideriver_id'] = $result[0]['guideriver_id'];
				$guideRivers[$c]['notes'] = $result[0]['notes'];
				$guideRivers[$c]['created_on'] = $result[0]['created_on'];
			} else {
				$guideRivers[$c]['guideriver_id'] = NULL;
				$guideRivers[$c]['notes'] = NULL;
				$guideRivers[$c]['created_on'] = NULL;
			}
			$c++;

		}
		
		return $guideRivers;

	}

  /**
   * Inserts guideriver into database.
   * @param int $rivertrip_id Id of river trip for whom river is being added
   * @param string $notes of added notes
   * @return result
   */
  function addGuideRiver($rivertrip_id, $notes) {

		$obj_id = $this->guide_id;

		$datetime = date("Y-m-d H:i:s");

    $this->db->insert("guide_rivers",  array(
        "user_id_fk"  		=> $obj_id,
        "rivertrip_id_fk" => $rivertrip_id,
        "notes"    				=> $notes
    ));

    $guideriver_id = $this->db->lastInsertId();

    $result = array(
        "guideriver_id"  => $guideriver_id,
        "notes"      		 => $notes,
        "start_date"  	 => format_date($datetime)
    );

    return json_encode($result);
  }

   /**
   * Deletes guideriver from database.
   * @param int $guideriver_id Id of line being deleted
   */
  function deleteGuideRiver($guideriver_id) {

		$this->db->delete("guide_rivers", "guideriver_id = :guideriver_id", array( "guideriver_id" => $guideriver_id));

  }
	
	/**
	 * un-approve all pay events for a guide by deleting them (trips and WH work) from the approvals table
	 * @return 0 if there was a problem and the number of rows affected if it was successful
	 */
	function unApproveGuide($visitor_id) {	
		
		$c = 0;
		foreach($this->isOnApproved() as $row) {
			if (!empty($row['trip_id_fk'])) {
				$t = unApproveTrip($row['trip_id_fk'], $visitor_id);
				if ($t){
					$c++;
				}
			}
		}
		
		if ($c > 0) {
			return $c;
		} else {
			return 0;
		}
	}

	/**
     * Get total number of guides that have this rate assigned to them
     * @return total number.
     */
    function guidesWithThisRate($payrate_id) {

        $query = "SELECT `user_id_fk`
                    FROM `guide_payrates`
                    WHERE `payrate_id_fk` = :payrate_id";

        $result = $this->db->select($query, array( 'payrate_id' => $payrate_id));

		return count($result);
	}

	/**
     * Determine if the guide is eligible for a bonus
     * @return true or false
     */
    function isBonusEligible() {

		$obj_id = $this->guide_id;

        $query = "SELECT `bonus_eligible`
                  FROM `guide_details`
                  WHERE `user_id_fk` = :user_id_fk 
                  AND `bonus_eligible` = 'Y'";

        $result = $this->db->select($query, array( 'user_id_fk' => $obj_id));

		if (count($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
     * Get all active guides
     * @return user_id, first and last names
     */
    function getActiveGuides() {

        $query = "SELECT `user_id`,`first_name`,`last_name`
                  FROM `as_user_details`
                  WHERE `user_id` IN
					(SELECT `user_id_fk` 
					FROM `guide_details` 
					WHERE `active_bool` = 'Y') 
				  ORDER BY `first_name` ASC";

        $result = $this->db->select($query);

		if (count($result) > 0) {
			return $result;
		} else {
			return false;
		}
	}

	/**
  * Get array of guides who have swamped trips in a given year
  * @return array of user_ids
  */
  function getSwampers($year) {

    //Get list of guides that have done a trip that has been at least approved
		$query = 'SELECT DISTINCT `user_id_fk`
  						FROM `approvals`
  						WHERE `guideevent_id_fk` IS NOT NULL
  						AND `role_id_fk` = 12
  						AND YEAR(STR_TO_DATE(`created_on`,"%Y-%c-%e")) = :year';
	  $swampers = $this->db->select($query, array( "year" => $year));

	  return $swampers;

  }

 	/**
  * Get array of river trips and days that a guide has swamped
  * @return array of river_trip_ids and days swamped
  */
  function getSwamperDays($year) {

		$obj_id = $this->guide_id;

    //Get list of guides that have done a trip that has been at least approved
		$query = 'SELECT trips.river_trips_fk, COUNT(*) as count
							FROM `approvals`
							JOIN `trips`
							ON approvals.trip_id_fk = trips.trip_id
							WHERE approvals.user_id_fk = :user_id_fk 
							AND approvals.role_id_fk = 12
							AND YEAR(STR_TO_DATE(approvals.event_date,"%Y-%c-%e")) = :year
							GROUP BY trips.river_trips_fk';
	  $swamper_trips = $this->db->select($query, array('user_id_fk' => $obj_id, "year" => $year));

	  return $swamper_trips;

  }

	/**
  * Get array of guides and work stats sorted by number of days worked
  * @return array of unique, sorted user_ids and stats
  */
  function getGuidesByRiverDays($start_date, $end_date) {

    //Get list of guides that have done a trip that has been at least approved
		$query = 'SELECT DISTINCT `user_id_fk`
  						FROM `approvals`
  						WHERE `guideevent_id_fk` IS NOT NULL
  						AND `event_date` BETWEEN :start AND :end';
	  $guides = $this->db->select($query, array( "start" => $start_date, "end" => $end_date));

	  // iterate through guide ids and append number of days
		$trip = new Trip();
		foreach ($guides as $key => $value) {
			//set id to current guide
			$this->set_guide_id($value['user_id_fk']);
			//get all approved trips for this guide
			$trips = $this->getAllApprovedTrips($start_date, $end_date);
			//reset total
			$total_river_days = 0;
			//iterate through trips
			foreach ($trips as $y) {
				$trip->set_trip_id($y['trip_id_fk']);
				//get river days
				$river_days = $trip->riverDays($y['trip_id_fk'], $value['user_id_fk']);
				//add to total river days
				$total_river_days += $river_days;
			}
			//add results to guide array
			$guides[$key]['river_days'] = $total_river_days;
		}

		usort($guides, function($a, $b) {
    return $a['river_days'] < $b['river_days'];
		});

		return $guides;
    
	}//End function

	/**
   * Get a guides current bonus amount
   * @return an amount, false if none.
   */
  function getAllApprovedTrips($start_date, $end_date) {

		$obj_id = $this->guide_id;

    $query = "SELECT `trip_id_fk`
              FROM `approvals`
              WHERE `user_id_fk` = :user_id_fk
              AND `guideevent_id_fk` IS NOT NULL
              AND `event_date` BETWEEN :start AND :end";

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id, "start" => $start_date, "end" => $end_date));

		return $result;

	}

	/**
   * Get a guides trip log for a given year
   * @return trip_id and role_id.
   */
  function getLoggedTrips($year) {

		$obj_id = $this->guide_id;

    $query = "SELECT DISTINCT `trip_id_fk`, `role_id_fk`
              FROM `approvals`
              WHERE `trip_id_fk` IS NOT NULL
              AND `timesheet_id_fk` IS NULL
              AND `user_id_fk` = :user_id_fk
              AND EXTRACT(YEAR FROM `event_date`) = :year
              ORDER BY `event_date` ASC";

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id, 'year' => $year));

		return $result;

	}

	/**
   * Get a guides locked trips and assoc trips for a given year
   * @return trip_id.
   */
  function getLockedTrips($year) {

		$obj_id = $this->guide_id;
		//$obj_id = 10;

    $query = 'SELECT DISTINCT `trip_id_fk`
              FROM `approvals`
              WHERE `trip_id_fk` IS NOT NULL
              AND `locked` IS NOT NULL
              AND `user_id_fk` = :user_id_fk
              AND EXTRACT(YEAR FROM `event_date`) = :year
              ORDER BY `event_date` ASC';

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id, 'year' => $year));

		return $result;
		//return "Here";

	}

  /**
   * Return an array of pay breakdown for a guide for a given year
   * @param year
   * @return array breakdown of pay for season
   */
  public function getYTDTripPay($year) {

		$obj_id = $this->guide_id;

		$ytd_trips = $this->getLockedTrips($year);

		$trip = new Trip();

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

		$tot_trip_days = 0;

		foreach ($ytd_trips as $t) {

			$trip->set_trip_id($t['trip_id_fk']);
			$gtp = $trip->getGuideTripPay($obj_id);

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
				$trip_days = $trip->riverDays($t['trip_id_fk'], $obj_id);
				$tot_trip_days += $trip_days;
			}

		}

    $ytd_guide_total = $base_pay + $tl_pay + $sat_pay + $bump_pay + $rig_pay + $shop_pay + $other_pay + $cert_pay;

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
      'ytd_guide_total' 	=> $ytd_guide_total,
      'ytd_river_days' 	=> $tot_trip_days
    );

    return $guide_trip_pay;
  }

	/**
   * Get a guides current seniority
   * @return an integer
   */
  function getSeniority() {

		$obj_id = $this->guide_id;

    $query = "SELECT `seniority`
              FROM `guide_details`
              WHERE `user_id_fk` = :user_id_fk";

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id));

		return $result[0]['seniority'];

	}

	/**
   * Get a guides current best phone number
   * @return an integer
   */
  function getBestPhone() {

		$obj_id = $this->guide_id;

    $query = "SELECT `phone_number`, `best_order`
              FROM `phone_numbers`
              WHERE `user_id_fk` = :user_id_fk
              ORDER BY `best_order` ASC";

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id));

		if (count($result) > 0) {
			return $result[0]['phone_number'];
		} else {
			return false;
		}

	}

} // end class

?>