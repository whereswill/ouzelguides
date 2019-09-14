<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title////////
	$title = "Approve Timesheet";

	//INITIALIZE ALL VARIABLE///////////
	//Necessary because not every page has all variables passed in both GET and POST///

	$timesheet_id = null;

	// clear TOTAL data variables
	$other_total = null;
	$timesheet_total = null;

	// clear OTHER data variables
	$timesheeteventuser_id_fk = null;
	$o_role_id_fk = null;
	$timesheetevent_notes = null;

	//set alert to success
	$alert = 1;

//GET INFO FOR timesheet DETAILS AREA//////////////////////
	
	if (!empty($_GET['timesheet_id'])) {
		$timesheet_id = $_REQUEST['timesheet_id'];
		
		//INSTANTIATE timesheet///// 
		$timesheet = new Timesheet();
		$timesheet->set_sheet_id($timesheet_id);
	}
	
	if ( null==$timesheet_id) {
		header("Location: /timesheets/timesheets.php");
		exit();
	} else {
		$data = $db->select("SELECT `timesheet_id`, `approved_on`, `locked_on` FROM `timesheets` WHERE `timesheet_id` = :timesheet_id ", array( "timesheet_id" => $timesheet_id ));
		$data = $data[0];
	}
	
//CHECK IF timesheet IS LOCKED AND APPROVE IF NOT
	
	if (!empty($data['locked_on'])){
		//if timesheet is locked, don't approve and warn visitor
	} else {
		
		if (!empty($data['approved_on'])){			
			$unapproved = unApproveTimesheet($timesheet_id, $visitor_id);	
		}

		//OTHER EVENTS

		$other_events = $db->select("SELECT * FROM other_events WHERE `timesheet_id_fk` = :timesheet_id ORDER BY otherevent_id ASC", array( "timesheet_id" => $timesheet_id ));
		//print_r($data);
		//$data = $data[0];
		foreach ($other_events as $row) {

			$event_date = $row['event_date'];

			$otherevent_id_fk = $row['otherevent_id'];

			$trip_id_fk = $row['trip_id_fk'];

			$user_id_fk = $row['user_id_fk'];

			$role_id_fk = $row['role_id_fk'];

			$base_pay = 0.00; //Base Pay

			$tl_pay = 0.00; //TL Pay

			$sat_pay = 0.00; //Sat Pay

			$bump_pay = 0.00; //Bump Pay

			$rig_pay = 0.00; //Rig Pay

			$shop_pay = 0.00; //Shop Pay

			$hours = $row['event_hours'];
	
			$other_pay = $row['event_amount'];

			$notes = $row['event_notes']; //Notes

			$cert_pay = 0.00; //Cert Pay
	
			//Total

			$bonus_pay = 0.00; //Bonus Pay
	
			$timesheet_array = array(
			    'event_date' => $event_date,
			    'timesheet_id_fk' => $timesheet_id,
			    'otherevent_id_fk' => $otherevent_id_fk,
			    'user_id_fk' => $user_id_fk,
			    'role_id_fk' => $role_id_fk,
			    'base_pay' => $base_pay,
			    'tl_pay' => $tl_pay,
			    'sat_pay' => $sat_pay,
			    'bump_pay' => $bump_pay,
			    'rig_pay' => $rig_pay,
			    'shop_pay' => $shop_pay,
			    'other_pay' => $other_pay,
			    'cert_pay' => $cert_pay,
			    'bonus_pay' => $bonus_pay,
			    'notes' => $notes,
			    'created_on' => $datetime,
			    'created_by' => $visitor_id,
				);
			if ($hours != NULL) {		
				$timesheet_array['event_hours'] = $hours;
			}
			if ($trip_id_fk) {		
				$timesheet_array['trip_id_fk'] = $trip_id_fk;
			}
				
			$success = $db->insert('approvals', $timesheet_array);
			
			///Set Alert
			if($success == 0) {
				$alert = 0;
			}
			
		} //end foreach other_events
		
		//add approval to timesheet
		$approval_array = array(
		    "approved_on" => "$datetime",
		    "approved_by" => "$visitor_id",
			);
			
		$success = $db->update('timesheets', $approval_array, "timesheet_id = :timesheet_id",array( "timesheet_id" => $timesheet_id));
		
		///Set Alert
		if($success == 0) {
			$alert = 0;
		}
		
	} //end else
	
header("Location: /payTimesheets/pay-timesheet.php?timesheet_id=$timesheet_id&alert=$alert");
exit();

?>
