<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
  }

	//variable to identify this page title////////
	$title = "Review Approved";

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

//GET INFO FOR TRIP DETAILS AREA//////////////////////
	
	if (!empty($_GET['trip_id'])) {
		$trip_id = $_REQUEST['trip_id'];
	}
	
//UNAPPROVE IF THAT WAS THE SELECTION///////////////
	
	if (isset($_GET['override_approval'])) {
		if ($_GET['override_approval'] == "true") {
			$unapproved = unApproveTrip($trip_id, $visitor_id);
		}
		header("Location: /payTrips/pay-trip.php?trip_id=$trip_id&alert=2");
		exit();
	}
	

	
	if ( null==$trip_id) {
		header("Location: /trips/trips.php");
		exit();
	} else {
		//INSTANTIATE TRIP///// 
		$trip = new Trip();
		$trip->set_trip_id($trip_id);
		
		$data = $trip->getTripDetails();
		
		$name = $trip->getTripName();
		
		$type = $trip->getTripType();
		
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

////ALERT AREA
	
	if ( !empty($_GET['alert'])) {
		if ($_GET['alert'] == 1) { ?>
			<div id="Alert" class="alert alert-success">
			   <strong>Success!</strong> The approval was successful.
			</div>
		<?php } else { ?>
			<div id="Alert" class="alert alert-warning">
			   <strong>Warning!</strong> There was a problem with your approval.
			</div>
		<?php }
	}
	
	if ($trip->isTripLocked()) { ?>
		<div id="Alert" class="alert alert-warning">
		   <strong>Trip Locked!</strong> This trip is LOCKED and can no longer be edited.
		</div>
	<?php } ?>

<!--TRIP DETAILS AREA-->

			<div class="row">
				<div class="col-sm-12"> <!--start content column-->
					<div class="form-horizontal" role="form">
						<div>	
							<div class="col-sm-12">
								<legend>Trip Details
									<?php if ($trip->isTripLocked()) {
										echo ' - <span class="currentR">Locked</span>';
									} else {
										echo ' - <span class="current">Approved</span>';
									} ?>
								</legend>
							</div>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<?php if ($target) { 
							echo '<a href="' . ASSession::get("target") . '" class="btn btn-warning pull-left btn-style-left">' . ASSession::get("return_btn") . '</a>';
							} else if ($trip->isTripApproved()) { ?>
							<a href="/trips/create-trip.php" class="btn btn-warning pull-left btn-style-left">Create Trip</a>
							<?php } ?>
							<a class="btn btn-primary pull-left btn-style-left" href="/trips/trips.php">Back To Trips</a>
							<?php if (!$trip->isTripLocked()) { ?>
							<a class="btn btn-info pull-left btn-style-left" href="/scheduleTrips/schedule-trip.php?trip_id=<?php echo $trip_id;?>">Edit Schedule</a>
							<?php }
							if ($trip->isTripApproved() && !$trip->isTripLocked()) { ?>
							<a class="btn btn-success pull-left btn-style-left" href="/payTrips/view-approved.php?trip_id=<?php echo $trip_id;?>&override_approval=true">Unapprove Pay</a>
							<?php } ?>
						</div> <!--end split column-->
						
						<?php include $_SERVER['DOCUMENT_ROOT'].'/scheduleTrips/trip-detailsGroup.php'; ?>
						
					</div><!--form-horizontal-->
				</div> <!--end content column-->
			</div><!--.row-->
			
<!--ALL SCHEDULED TABLE AREA-->

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-12">
						<legend>All Scheduled</legend>
					</div>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Name</th>
								<th>Role</th>
								<th>Base</th>
								<th>TL</th>
								<th>Sat.</th>
								<th>Bumps</th>
								<th>Rig</th>
								<th>Shop</th>
								<th>Other</th>
								<th>Certs</th>
								<th>Total</th>
								<th>Bonus</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
							$guide_events = $db->select("SELECT * FROM approvals WHERE `trip_id_fk` = :trip_id ORDER BY `approval_id` ASC", array( "trip_id" => $trip_id ));
							if (count($guide_events) > 0) {
							
								///////INSTANTIATE GUIDE OBJECT///////////
								$guide = new Guide();
								foreach ($guide_events as $row) {
								
									$guide->set_guide_id($row['user_id_fk']);
								
									echo '<tr>' . "\n";

									echo '<td>';
									if ($row['timesheet_id_fk']) {
										echo '<span class="icon-user glyphicon glyphicon-list-alt"></span> ';
									}								
									echo $guide->getUserName() . '</td>' . "\n";
								
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
								
									$guide_total = $row['base_pay'];
									echo '<td>'. formatMoney($row['base_pay']) . '</td>' . "\n"; //Base Pay

									$guide_total = $guide_total + $row['tl_pay'];
									echo '<td>'. formatMoney($row['tl_pay']) . '</td>' . "\n"; //TL Pay

									$guide_total = $guide_total + $row['sat_pay'];
									echo '<td>'. formatMoney($row['sat_pay']) . '</td>' . "\n"; //Sat Pay

									$guide_total = $guide_total + $row['bump_pay'];
									echo '<td>'. formatMoney($row['bump_pay']) . '</td>' . "\n"; //Bump Pay

									$guide_total = $guide_total + $row['rig_pay'];
									echo '<td>'. formatMoney($row['rig_pay']) . '</td>' . "\n"; //Rig Pay

									$guide_total = $guide_total + $row['shop_pay'];
									echo '<td>'. formatMoney($row['shop_pay']) . '</td>' . "\n"; //Shop Pay

									$guide_total = $guide_total + $row['other_pay'];
									echo '<td>'. formatMoney($row['other_pay']) . ' ' . "\n"; //Other Pay
									if (!empty($row['notes'])) {
										echo '<div class="note_popup" title="Notes" data-placement="left" data-content="' . $row['notes'] . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div></td>' . "\n"; //Info Icon
									}
								
									$guide_total = $guide_total + $row['cert_pay'];
									echo '<td>'. formatMoney($row['cert_pay']) . '</td>' . "\n"; //Cert Pay
								
									echo '<td>'. formatMoney($guide_total) . '</td>' . "\n"; //Total
									
									echo '<td>'. formatMoney($row['bonus_pay']) . '</td>' . "\n"; //Bonus Pay
									echo '</tr>' . "\n";
								
									$trip_total = $trip_total + $guide_total;
								}
							}
							
							echo '<tr class="total-row">' . "\n";
							echo '<td></td>' . "\n";
							echo '<td></td>' . "\n";
							echo '<td></td>' . "\n"; //Base Pay
							echo '<td></td>' . "\n"; //TL Pay
							echo '<td></td>' . "\n"; //Sat Pay
							echo '<td></td>' . "\n"; //Bump Pay
							echo '<td></td>' . "\n"; //Rig Pay
							echo '<td></td>' . "\n"; //Shop Pay
							echo '<td></td>' . "\n"; //Other Pay
							echo '<td>Trip Total</td>' . "\n"; //Cert Pay
							echo '<td>'. formatMoney($trip_total) . '</td>' . "\n"; //Total
							echo '<td></td>' . "\n"; //Bonus Pay
							echo '</tr>' . "\n";
							?>
						</tbody>
					</table>
				</div>
			</div><!--row-->

	<?php
		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
	?>

	</body>
</html>