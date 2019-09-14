<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title////////
	$title = "Approve Pay";

	//INITIALIZE ALL VARIABLE///////////
	//Necessary because not every page has all variables passed in both GET and POST///

	$trip_id = null;

	// clear TOTAL data variables
	$guide_total = null;
	$other_total = null;
	$trip_total = null;

	// clear GUIDE data variables
	$user_id_fk = null;
	$g_role_id_fk = null;
	$rigger_bool = null;
	$food_shopper_bool = null;

	// clear OTHER data variables
	$tripeventuser_id_fk = null;
	$o_role_id_fk = null;
	$tripevent_notes = null;

	//set alert to success
	$alert = 1;

//GET INFO FOR TRIP DETAILS AREA//////////////////////
	
	if (!empty($_GET['trip_id'])) {
		$trip_id = $_REQUEST['trip_id'];
		
		//INSTANTIATE TRIP///// 
		$trip = new Trip();
		$trip->set_trip_id($trip_id);
		$event_date = $trip->getTripEventDate();
	}
	
	if ( null==$trip_id) {
		header("Location: /trips/trips.php");
		exit();
	} else {
		$data = $db->select("SELECT `trip_id`, `approved_on`, `locked_on` FROM `trips` WHERE `trip_id` = :trip_id ", array( "trip_id" => $trip_id ));
		//print_r($data);
		$data = $data[0];
	}
	
//CHECK IF TRIP IS LOCKED AND APPROVE IF NOT
	
	if (!empty($data['locked_on'])){
		//if trip is locked, don't approve and warn visitor
	} else {
		
		if (!empty($data['approved_on'])){			
			$unapproved = unApproveTrip($trip_id, $visitor_id);	
		}
		
		$guide_events = $db->select("SELECT * FROM guide_events WHERE `trip_id_fk` = :trip_id ORDER BY guideevent_id ASC", array( "trip_id" => $trip_id ));
		
		$pay = new PayTrip($trip, $guide_events[0]['user_id_fk']);
		
		//GUIDE EVENTS
		
		foreach ($guide_events as $row) {

			$guideevent_id_fk = $row['guideevent_id'];
	
			$role_id_fk = $row['role_id_fk'];
			
			$pay->set_guide_id($row['user_id_fk']);
			$user_id_fk = $pay->get_guide_id();
	
			if(!isSwamper($row['role_id_fk'])){
				$base_pay = $pay->getBasePay();
				$guide_total = $base_pay;
			} else {
				$base_pay = 0.00; //Base Pay
			}
	
			if(isTL($row['role_id_fk'])){
				$tl_pay = $pay->getTLPay();
				$guide_total = $guide_total + $tl_pay;
			} else {
				$tl_pay = 0.00; //TL Pay
			}

			if($trip->isSat()){
				$sat_pay = $pay->getSatPay();
				$guide_total = $guide_total + $sat_pay;
			} else {
				$sat_pay = 0.00; //Sat Pay
			}

			if(!isSwamper($row['role_id_fk'])){
				$bumps = $pay->getBumpPay(); //Bump Pay
				$bump_pay = $bumps['pay'];
				$guide_total = $guide_total + $bump_pay;
			} else {
				$bump_pay = 0.00; //Bump Pay
			}									

			if(!$trip->isDay() && $row['rigger_bool'] == 'Y'){
				$rig_pay = $pay->getRigPay();
				$guide_total = $guide_total + $rig_pay;
			} else {
				$rig_pay = 0.00; //Rig Pay
			}

			if($row['food_shopper_bool'] == 'Y'){
				$shop_pay = $pay->getShopPay();
				$guide_total = $guide_total + $shop_pay;
			} else {
				$shop_pay = 0.00; //Shop Pay
			}									

			$adjust_pay = (empty($row['adjust_amount']) ? 0.00 : $row['adjust_amount']);
			//$adjust_pay = $row['adjust_amount'];
			$guide_total = $guide_total + $adjust_pay;
			
			//$notes = $row['adjust_notes'];
			$notes = (is_null($row['adjust_notes']) ? "" : $row['adjust_notes']);
	
			$cert_pay = $pay->getCertPay();
			$guide_total = $guide_total + $cert_pay; //Cert Pay
	
			//Total
			
			$bonus_pay = $pay->getBonusPay(); //Bonus Pay
	
			$trip_array = array(
			    'guideevent_id_fk' => $guideevent_id_fk,
			    'event_date' => $event_date,
			    'trip_id_fk' => $trip_id,
			    'user_id_fk' => $user_id_fk,
			    'role_id_fk' => $role_id_fk,
			    'base_pay' => $base_pay,
			    'tl_pay' => $tl_pay,
			    'sat_pay' => $sat_pay,
			    'bump_pay' => $bump_pay,
			    'rig_pay' => $rig_pay,
			    'shop_pay' => $shop_pay,
			    'other_pay' => $adjust_pay,
			    'cert_pay' => $cert_pay,
			    'bonus_pay' => $bonus_pay,
			    'notes' => $notes,
			    'created_on' => $datetime,
			    'created_by' => $visitor_id,
				);
				
			$success = $db->insert('approvals', $trip_array);
			
			//Set Alert
			if($success == 0) {
				$alert = 0;
			}
				
		} //end foreach guide_events

		//OTHER EVENTS

		$other_events = $db->select("SELECT * FROM other_events WHERE `trip_id_fk` = :trip_id AND `timesheet_id_fk` IS NULL ORDER BY otherevent_id ASC", array( "trip_id" => $trip_id ));

		foreach ($other_events as $row) {

			$otherevent_id_fk = $row['otherevent_id'];

			$user_id_fk = $row['user_id_fk'];

			$role_id_fk = $row['role_id_fk'];

			$base_pay = 0.00; //Base Pay

			$tl_pay = 0.00; //TL Pay

			$sat_pay = 0.00; //Sat Pay

			$bump_pay = 0.00; //Bump Pay

			$rig_pay = 0.00; //Rig Pay

			$shop_pay = 0.00; //Shop Pay
	
			$other_pay = (empty($row['event_amount']) ? 0.00 : $row['event_amount']);

			$notes = (empty($row['event_notes']) ? "" : $row['event_notes']);

			$cert_pay = 0.00; //Cert Pay
	
			//Total

			$bonus_pay = 0.00; //Bonus Pay
	
			$trip_array = array(
			    'trip_id_fk' => $trip_id,
			    'event_date' => $event_date,
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
				
			$success = $db->insert('approvals', $trip_array);
			
			//Set Alert
			if($success == 0) {
				$alert = 0;
			}
			
		} //end foreach other_events
		
		//add approval to trip
		$approval_array = array(
		    'approved_on' => $datetime,
		    'approved_by' => $visitor_id,
			);
			
		$success = $db->update('trips', $approval_array, "trip_id = :trip_id",array( "trip_id" => $trip_id));
		
		///Set Alert
		if($success == 0) {
			$alert = 0;
		}
		
	} //end else
	
header("Location: /payTrips/pay-trip.php?trip_id=$trip_id&alert=$alert");
exit();

?>
