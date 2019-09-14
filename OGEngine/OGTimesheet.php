<?php
 
/**
 * Timesheet class
 *
 * This is a class to deal with Timesheet Work
 *
 * @author Will Sharp <whereswill@bendcable.com>
 * @copyright 2014 Back Alley
 */
class Timesheet
{

	/**
  * @var ID of Trip instance
  */
	public $timesheet_id;
	
	/**
  * @var Instance of ASDatabase class
  */
  private $db = null;
	
	function __construct() {  
		
  	$this->db = ASDatabase::getInstance(); 
		          
	}

	public function set_sheet_id($new_sheetId) { 
		
		$this->timesheet_id = $new_sheetId;
		  
	}
	
	public function get_sheet_id() {

		return $this->timesheet_id;
		
	}
	
	/**
  * Get user id for a timesheet
  * @return user_id
  */
	public function getSheetUser() {
		
		$obj_id = $this->timesheet_id;
		
		$result = $this->db->select("SELECT `user_id_fk` FROM `timesheets` WHERE `timesheet_id` = :timesheet_id LIMIT 1", array( "timesheet_id" => $obj_id ));
		return $result[0]['user_id_fk'];
		
	}
	
	/**
  * Get the first event date for a timesheet
  * @return earliest date
  */
	public function getSortDate() {
		
		$obj_id = $this->timesheet_id;
		
		$result = $this->db->select("SELECT UNIX_TIMESTAMP(`event_date`) as sortdate FROM `other_events` WHERE `timesheet_id_fk` = :timesheet_id ORDER BY sortdate ASC", array( "timesheet_id" => $obj_id ));
		
		if (count($result) == 0) {
			$date = new DateTime();
			return $date->getTimestamp();
		} else {
			return $result[0]['sortdate'];
		}

	}

	/**
  * Get all details for a trip
  * @return array of details
  */
	public function getTimesheetDetails() {
		
		$obj_id = $this->timesheet_id;
		
		$result = $this->db->select("SELECT * FROM `timesheets` WHERE `timesheet_id` = :timesheet_id LIMIT 1", array( "timesheet_id" => $obj_id ));
		return $result[0];
		
	}

	/**
  * Get first and last dates on a timesheet
  * @return the formatted date range
  */
	public function getTimesheetDates() {
		
		$obj_id = $this->timesheet_id;

    $query = "SELECT `event_date`
              FROM `other_events`
              WHERE `timesheet_id_fk` = :timesheet_id
              ORDER BY `event_date` ASC";

    $result = $this->db->select($query, array( 'timesheet_id' => $obj_id));
		
		if (count($result) > 1){
			$f = current($result);
    	$first = $f['event_date'];
			//print_r($first);
			$l = end($result);
			$last = $l['event_date'];
			//print_r($last);
			return format_short_date($first) . " - " . format_date($last);
		} elseif (count($result) == 1) {
			return format_date($result[0]['event_date']);
		} else {
			return "None";
		}
		
	}

	/**
  * Get count of events for a timesheet
  * @return the count
  */
	public function getSheetCount() {
	
		$obj_id = $this->timesheet_id;
	
    $query = "SELECT `user_id_fk`
              FROM `other_events`
              WHERE `timesheet_id_fk` = :timesheet_id";

    $result = $this->db->select($query, array( 'timesheet_id' => $obj_id));
		//print_r($result);

		return count($result);
		
	}

	/**
  * Check that user_ids match on all events and on timesheet
  * @return the true if they do and false if names do not match
  */
	public function checkSheetUser($user_id) {
	
		$obj_id = $this->timesheet_id;
	
    $query = "SELECT `user_id_fk`
              FROM `other_events`
              WHERE `timesheet_id_fk` = :timesheet_id";

    $result = $this->db->select($query, array( 'timesheet_id' => $obj_id));
		//print_r($result);

		foreach($result as $r) {
			if (!$r['user_id_fk'] == $user_id) {
				return false;
			}
		}

		return true;
		
	}

	/**
  * Determine if there are unapproved timesheets before the passed date
  * @return return number or false
  */
	public function areUnapproved($end_date) {

    $query = "SELECT `timesheet_id`
              FROM `timesheets`
              WHERE `approved_on` IS NULL";

    $result = $this->db->select($query);

    $events_query = "SELECT `otherevent_id`
          		 			 FROM `other_events`
          					 WHERE `timesheet_id_fk` = :timesheet_id
          					 AND event_date < :end_date";

		$count = 0;
    foreach ($result as $key) {
    	$events = $this->db->select($events_query, array('timesheet_id' => $key['timesheet_id'], 'end_date' => $end_date));
			if (count($events) > 0) {
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
  * Determine if a timesheet is Locked for editing (has been paid)
  * @return true or false.
  */
  public function isTimesheetLocked() {
	
		$obj_id = $this->timesheet_id;
	
        $query = "SELECT `locked_on`
                    FROM `timesheets`
                    WHERE `timesheet_id` = :timesheet_id LIMIT 1";

    $result = $this->db->select($query, array( 'timesheet_id' => $obj_id));
		$result = $result[0];
	
		if ($result['locked_on'] <> NULL) {
			return $result['locked_on'];
		} else {
			return false;
		}
	
	}

	/**
  * Determine if a trip associated with a timesheet is Locked for editing (has been paid)
  * @return true or false.
  */
  public function isTimesheetTripLocked() {
	
		$obj_id = $this->timesheet_id;
	
    $query = "SELECT `trip_id_fk`
              FROM `other_events`
              WHERE `timesheet_id_fk` = :timesheet_id
              AND `trip_id_fk` IS NOT NULL";

    $result = $this->db->select($query, array( 'timesheet_id' => $obj_id));
	
		$trip = new Trip();
		foreach ($result as $key) {
			$trip->set_trip_id($key['trip_id_fk']);
			if ($trip->isTripLocked()) {
				return true;
			}
		}
		return false;
	
	}

	/**
	 * lock the timesheet and the approvals for all of the timesheet events
	 */
	function lockTimesheet($visitor_id, $lock_date) {	
	
		$obj_id = $this->timesheet_id;

		$lt_array = array(
			"locked_on" => "$lock_date",
			);
		$timesheet_lock = $this->db->update("timesheets", $lt_array, "timesheet_id = :timesheet_id", array( "timesheet_id" => $obj_id));

		$la_array = array(
			"locked" => "$lock_date",
			);
		$app_lock = $this->db->update("approvals", $la_array, "timesheet_id_fk = :timesheet_id", array( "timesheet_id" => $obj_id));

	}	
	
	/**
	* Determine which timesheeets have at least one pay event that occurs on or before end date
	* @return return number or false
	*/
	public function getApprovedTimesheetsBeforeEnd($date) {

		//Get timesheets that are approved
    $query = "SELECT `timesheet_id`
		  				FROM `timesheets` 
		  				WHERE `approved_on` IS NOT NULL
		  				AND `locked_on` IS NULL";

    $result = $this->db->select($query);

    //Get events for timesheet
  	$sheet_query = "SELECT `otherevent_id`
				  					FROM `other_events` 
				  					WHERE `event_date` <= :end_date
				  					AND `timesheet_id_fk` = :timesheet_id";

		$app_sheets = [];

		//for each timesheet, check if any event occurs before end date
    foreach ($result as $key => $value) { 

			$events = $this->db->select($sheet_query, array("end_date" => $date, "timesheet_id" => $value['timesheet_id']));

			// //if so, add it to array
			if (count($events) > 0) {
				$app_sheets[$key] = $value['timesheet_id'];
			}

    } 

		return $app_sheets;
		
	}

	/**
	* Determine if timesheet has at least one pay event occur on or before the end date
	* @return true or false
	*/
	public function isApprovedTimesheetBeforeEnd($date) {

		$obj_id = $this->timesheet_id;

    $query = "SELECT event_date
		  				FROM `other_events` 
		  				WHERE `timesheet_id_fk` = :timesheet_id
		  				AND event_date <= :end_date";

    $result = $this->db->select($query, array("timesheet_id" => $obj_id, "end_date" => $date));

    if (count($result) > 0) {
    	return true;
    } else {
    	return false;
    }
		
	}
	
	/**
  * Determine if a timesheet is Approved but not Locked
  * @return true or false.
  */
  public function isTimesheetApproved() {
	
		$obj_id = $this->timesheet_id;

    $query = "SELECT `locked_on`, `approved_on`
              FROM `timesheets`
              WHERE `timesheet_id` = :timesheet_id LIMIT 1";

    $result = $this->db->select($query, array( 'timesheet_id' => $obj_id));
		
		if (count($result) == 1){
			$result = $result[0];
		} else {
			return false;
		}
	
		//echo $result['locked_on'];
	
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
	
		$obj_id = $this->timesheet_id;

    $query = "SELECT `approved_on`
              FROM `timesheets`
              WHERE `timesheet_id` = :timesheet_id LIMIT 1";

    $result = $this->db->select($query, array( 'timesheet_id' => $obj_id));
		
		if (count($result) == 1){
			$result = $result[0];
			return $result['approved_on'];
		} else {
			return false;
		}

	}

  /**
   * Return an array of pay breakdown for a user for a given timesheet
   * @param none
   * @return array breakdown of pay for timesheet
   */
  public function getUserTimesheetPay() {

		$obj_id = $this->timesheet_id;

    $query = "SELECT `other_pay`
              FROM `approvals`
              WHERE `timesheet_id_fk` = :timesheet_id
              AND `trip_id_fk` IS NULL";

    $approvals = $this->db->select($query, array( 'timesheet_id' => $obj_id));
		
		$other_pay = 0;

    foreach ($approvals as $a) {
			$other_pay += $a['other_pay'];
    }

    $query1 = "SELECT DISTINCT `event_date`
              FROM `approvals`
              WHERE `timesheet_id_fk` = :timesheet_id";

    $wh_days = $this->db->select($query1, array( 'timesheet_id' => $obj_id));
    $wh_days = count($wh_days);

    $user_wh_pay = array(
      'wh_pay' 	=> $other_pay,
      'wh_days' 	=> $wh_days
    );

    return $user_wh_pay;
  }

}

?>