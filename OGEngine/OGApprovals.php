<?php

/**
 * Approval class.
 */

//////////////////////////UN-APPROVE//////////////////////////////////////////////

	/**
	 * Not Used
	 * un-approve all pay events (trips, timesheets and approvals) by deleting them from the approvals table
	 * @return 0 if there was a problem and the number of rows affected if it was successful
	 */
	function unApproveAll($visitor_id) {	

		$db = ASDatabase::getInstance();

    $query = "SELECT `trip_id` 
							FROM `trips`
							WHERE `approved_on` IS NOT NULL AND `locked_on` IS NULL";

    $trip_result = $db->select($query);
		
		//$guide_success = false;
		foreach ($trip_result as $r) {
			$guide_success = unApproveTrip($r['trip_id'], $visitor_id);
			if ($guide_success) {$success = true;}
		}

    $query = "SELECT `timesheet_id`
              FROM `timesheets`
              WHERE `approved_on` IS NOT NULL AND `locked_on` IS NULL";

    $timesheet_result = $db->select($query);

		//$wh_success = false;
		foreach ($timesheet_result as $r) {
			$ts_success = unApproveTimesheet($r['timesheet_id'], $visitor_id);
			if ($ts_success) {$success = true;}
		}

		return $success;
	}
	
	/**
	 * un-approve all pay events by deleting them from the approvals table
	 * @return 0 if there was a problem, 1 if it was successful, and none if there were no trips
	 */
	
	function unApproveAllTrips($visitor_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `trip_id` 
							FROM `trips`
							WHERE `approved_on` IS NOT NULL AND `locked_on` IS NULL";

    $trip_result = $db->select($query);
				
		//$guide_success = false;
		if (!empty($trip_result)) {
			foreach ($trip_result as $r) {
				$guide_success = unApproveTrip($r['trip_id'], $visitor_id);
				if ($guide_success) {$success = true;}
			}
		} else {
			$success = 'none';
		}

		return $success;
	
	}

	/**
	 * un-approve all pay events for a trip by deleting them from the approvals table
	 * @return 0 if there was a problem and the number of rows affected if it was successful
	 */
	function unApproveTrip($trip_id, $visitor_id) {	

		$db = ASDatabase::getInstance();
		
		$q = $db->deleteAll("approvals","trip_id_fk = :trip_id AND locked IS NULL", array( "trip_id" => $trip_id ));
		if ($q == 0) {
			$q = 0; //something went wrong
		} else {
			$q = count($q); //number of rows affected
		}
		
		if($q > 0) {
			$date = new DateTime(); //this returns the current date time
			$datetime = $date->format("Y-m-d H:i:s");
			
			$ua_array = array(
									"approved_on" => NULL,
							    "approved_by" => NULL,
							    "updated_on" => "$datetime",
							    "updated_by" => "$visitor_id",
									);
			$t = $db->update("trips", $ua_array, "trip_id = :trip_id AND locked_on IS NULL", array( "trip_id" => $trip_id));
			
			if ($t && count($t) == 1) { //TEST THIS
				return TRUE;
			} else {
				return FALSE;
			}
			
		} else {
			return FALSE; //if there was a problem with the deletion from approvals
		}

	}	
	
	/**
	 * un-approve all pay events for a trip name by deleting them from the approvals table
	 * @return 0 if there was a problem and the number of rows affected if it was successful
	 */
	function unApproveTripName($rivertrip_id, $visitor_id) {	

		$db = ASDatabase::getInstance();
		
		$query = "SELECT `trip_id`
              FROM `trips`
              WHERE `river_trips_fk` = :rivertrip_id AND `locked_on` IS NULL";

    $result = $db->select($query, array( 'rivertrip_id' => $rivertrip_id));
		$c = count($result);
		
		foreach ($result as $r) {
			$success = unApproveTrip($r['trip_id'], $visitor_id);
			if ($success) {
				$c = --$c;
			}
		}
		
		if ($c == 0) {
			return 1; //true
		} else {
			return 0; //false
		}

	}
	
	/**
	 * un-approve all pay events for a trip type by deleting them from the approvals table
	 * @return 0 if there was a problem and the number of rows affected if it was successful
	 */
	function unApproveTripType($triptype_id, $visitor_id) {	

		$db = ASDatabase::getInstance();

		$query = "SELECT `trip_id`
	            FROM `trips`
	            WHERE `trip_types_fk` = :triptype_id AND `locked_on` IS NULL";

    $result = $db->select($query, array( 'triptype_id' => $triptype_id));
		$c = count($result);

		foreach ($result as $r) {
			$success = unApproveTrip($r['trip_id'], $visitor_id);
			if ($success) {
				$c = --$c;
			}
		}

		if ($c == 0) {
			return 1; //true
		} else {
			return 0; //false
		}
	}
	
	/**
	 * un-approve all pay for a warehouse event by deleting them from the approvals table
	 * @return 0 if there was a problem and the number of rows affected if it was successful
	 */
	function unApproveTimesheet($timesheet_id, $visitor_id) {	

		$db = ASDatabase::getInstance();
		
		$q = $db->deleteAll("approvals","timesheet_id_fk = :timesheet_id AND locked IS NULL", array( "timesheet_id" => $timesheet_id ));
		if ($q == 0) {
			$q = 0; //something went wrong
		} else {
			$q = count($q); //number of rows affected
		}
		
		if($q > 0) {
			$date = new DateTime(); //this returns the current date time
			$datetime = $date->format("Y-m-d H:i:s");
			
			$ua_array = array(
									"approved_on" => NULL,
							    "approved_by" => NULL,
							    "updated_on" => "$datetime",
							    "updated_by" => "$visitor_id",
									);
			$t = $db->update("timesheets", $ua_array, "timesheet_id = :timesheet_id AND locked_on IS NULL", array( "timesheet_id" => $timesheet_id));
			
			if ($t && count($t) == 1) { //TEST THIS
				return TRUE;
			} else {
				return FALSE;
			}
			
		} else {
			return FALSE; //if there was a problem with the deletion from approvals
		}

	}	
	
	/**
	 * un-approve all pay events for a role by deleting them from the approvals table
	 * @return 0 if there was a problem and the number of rows affected if it was successful
	 */
	function unApproveRole($role_id, $visitor_id) {	

		$db = ASDatabase::getInstance();

    $query = "SELECT trips.trip_id 
							FROM `trips` 
							JOIN `guide_events` 
							ON trips.trip_id = guide_events.trip_id_fk 
							WHERE guide_events.role_id_fk = :role_id AND trips.locked_on IS NULL";

     $trip_result = $db->select($query, array( 'role_id' => $role_id));
		
		//$guide_success = false;
		foreach ($trip_result as $r) {
			$guide_success = unApproveTrip($r['trip_id'], $visitor_id);
			if ($guide_success) {$success = true;}
		}
		
		$query = "SELECT trips.trip_id 
							FROM `trips` 
							JOIN `other_events` 
							ON trips.trip_id = other_events.trip_id_fk 
							WHERE other_events.role_id_fk = :role_id AND trips.locked_on IS NULL";

     $other_result = $db->select($query, array( 'role_id' => $role_id));

		//$other_success = false;
		foreach ($other_result as $r) {
			$other_success = unApproveTrip($r['trip_id'], $visitor_id);
			if ($other_success) {$success = true;}
		}

		return $success;
	}
	
//////////////////////////////////DETERMINE IF LOCKED////////////////////////////////////
	
	/**
   * Determine if a river name is on a trip Locked for editing (has been paid)
   * @return true or false.
   */
  function riverNameLocked($rivertrip_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `locked_on`
              FROM `trips`
              WHERE `river_trips_fk` = :rivertrip_id AND `locked_on` IS NOT NULL";

    $result = $db->select($query, array( 'rivertrip_id' => $rivertrip_id));
		$result = count($result);

		//echo $result['locked_on'];

		if ($result > 0) {
			return true;//if Locked
		} else {
			return false;
		}

	}
	
	/**
   * Determine if a role is on a trip or w/h work Locked for editing (has been paid)
   * @return true or false.
   */
  function roleLocked($role_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT trips.locked_on
		          FROM `trips`
							JOIN `guide_events`
							ON trips.trip_id = guide_events.trip_id_fk
		          WHERE guide_events.role_id_fk = :role_id AND trips.locked_on IS NOT NULL"; //No Locked_on in trips

    $trip = $db->select($query, array( 'role_id' => $role_id));
		$trip = count($trip);
		
		$query = "SELECT trips.locked_on
		          FROM `trips`
							JOIN `other_events`
							ON trips.trip_id = other_events.trip_id_fk
		          WHERE other_events.role_id_fk = :role_id AND trips.locked_on IS NOT NULL"; //No Locked_on in trips

    $other = $db->select($query, array( 'role_id' => $role_id));
		$other = count($other);
		
		$query = "SELECT timesheets.locked_on
		          FROM `timesheets`
							JOIN `other_events`
							ON timesheets.timesheet_id = other_events.timesheet_id_fk
		          WHERE other_events.role_id_fk = :role_id AND timesheets.locked_on IS NOT NULL"; //No Locked_on in trips

    $sheet = $db->select($query, array( 'role_id' => $role_id));
		$sheet = count($sheet);

		$locked = $trip + $other + $sheet;
		if ($locked > 0) {
			return true;//if Locked
		} else {
			return false;
		}

	}
	
	/**
   * Determine if a trip type is on a trip Locked for editing (has been paid)
   * @return true or false.
   */
  function tripTypeLocked($triptype_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `locked_on`
              FROM `trips`
              WHERE `trip_types_fk` = :triptype_id AND `locked_on` IS NOT NULL";

    $result = $db->select($query, array( 'triptype_id' => $triptype_id));
		$result = count($result);

		//echo $result['locked_on'];

		if ($result > 0) {
			return true;//if Locked
		} else {
			return false;
		}

	}

	/**
   * Determine if a trip type is on a trip Locked for editing (has been paid)
   * @return true or false.
   */
  function setLockDate($visitor_id, $lock_date, $end_date) {

		$db = ASDatabase::getInstance();			

		$lockdate_array = array(
			    						"end_date" => "$end_date",
											"lock_date" => "$lock_date",
											"locked_by" => "$visitor_id",
											);
		$q = $db->insert('lock_dates', $lockdate_array);

	}
	
///////////////////////////////DETERMINE IF APPROVED/////////////////////////////////////

  /**
   * Determine if any trips are Approved but not Locked
   * @return true or false.
   */
  function areTripsApproved() {

		$db = ASDatabase::getInstance();

    $query = "SELECT `trip_id`
              FROM `trips`
              WHERE `approved_on` IS NOT NULL && `locked_on` IS NULL";

    $result = $db->select($query);

		//echo $result;

		if (count($result) == 0) {
			return false;
		} else {
			return $result;
		}
	}
	
  /**
   * Determine if a Role is Approved and not Locked
   * @return true or false.
   */
  function isRoleApproved($role_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT trips.approved_on, trips.locked_on 
							FROM `trips` 
							JOIN `guide_events` 
							ON trips.trip_id = guide_events.trip_id_fk 
							WHERE guide_events.role_id_fk = :role_id";

    $trip_result = $db->select($query, array( 'role_id' => $role_id));

    $query = "SELECT trips.approved_on, trips.locked_on 
							FROM `trips` 
							JOIN `other_events` 
							ON trips.trip_id = other_events.trip_id_fk 
							WHERE other_events.role_id_fk = :role_id";

    $other_result = $db->select($query, array( 'role_id' => $role_id));

		//print_r($result);
		$approved = false;
		foreach ($trip_result as $r) {
			if ($r['approved_on'] && !$r['locked_on']) {
				$approved = true;
			}
		}
		foreach ($other_result as $o) {
			if ($o['approved_on'] && !$o['locked_on']) {
				$approved = true;
			}
		}
		return $approved;
	}
	
  /**
   * Determine if a trip name is Approved and not Locked
   * @return true or false.
   */
  function isNameApproved($rivertrip_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `approved_on`, `locked_on`
              FROM `trips`
              WHERE `river_trips_fk` = :rivertrip_id";

    $result = $db->select($query, array( 'rivertrip_id' => $rivertrip_id));

		//print_r($result);
		$approved = "false";
		foreach ($result as $r) {
			if ($r['approved_on'] && !$r['locked_on']) {
				return true;
			}
		}
	}
	
  /**
   * Determine if a trip type is Approved and not Locked
   * @return true or false.
   */
  function isTypeApproved($triptype_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `approved_on`, `locked_on`
              FROM `trips`
              WHERE `trip_types_fk` = :triptype_id";

    $result = $db->select($query, array( 'triptype_id' => $triptype_id));

		//print_r($result);
		$approved = "false";
		foreach ($result as $r) {
			if ($r['approved_on'] && !$r['locked_on']) {
				return true;
			}
		}
	}

  /**
   * Get the event date for an approval line
   * @return date or false.
   */
  function getEventDate($otherevent_id_fk) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `event_date`
              FROM `other_events`
              WHERE `otherevent_id` = :otherevent_id_fk
              LIMIT 1";

    $result = $db->select($query, array( 'otherevent_id_fk' => $otherevent_id_fk));

		if (count($result) > 0) {
			return $result[0]['event_date'];
		} else {
			return false;
		}
	}

?>
