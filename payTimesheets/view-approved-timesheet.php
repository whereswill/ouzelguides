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

	$timesheet_id = null;

	// clear TOTAL data variables
	$other_total = null;
	$timesheet_total = null;

	// clear OTHER data variables
	$timesheeteventuser_id_fk = null;
	$o_role_id_fk = null;
	$timesheetevent_notes = null;

//GET INFO FOR timesheet DETAILS AREA//////////////////////
	
	if (!empty($_GET['timesheet_id'])) {
		$timesheet_id = $_REQUEST['timesheet_id'];
		//echo $timesheet_id;
	}
	
//UNAPPROVE IF THAT WAS THE SELECTION///////////////
	
	if (isset($_GET['override_approval'])) {
		if ($_GET['override_approval'] == "true") {
			$unapproved = unApproveTimesheet($timesheet_id, $visitor_id);
			//echo $unapproved;
		}
		header("Location: /payTimesheets/pay-timesheet.php?timesheet_id=$timesheet_id&alert=2");
		exit();
	}
	

	
	if ( null==$timesheet_id) {
		header("Location: /timesheets/timesheets.php");
		exit();
	} else {
		//INSTANTIATE timesheet///// 
		$timesheet = new Timesheet();
		$timesheet->set_sheet_id($timesheet_id);
		
		$data = $timesheet->getTimesheetDetails();

		$user_id = $timesheet->getSheetUser();
		$user = new User();
		$user->set_user_id($user_id);
		
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
	
	if ($timesheet->istimesheetLocked()) { ?>
		<div id="Alert" class="alert alert-warning">
		   <strong>Timesheet Locked!</strong> This timesheet is LOCKED and can no longer be edited.
		</div>
	<?php } ?>

<!--timesheet DETAILS AREA-->

			<div class="row">
				<div class="col-sm-12"> <!--start content column-->
					<div class="form-horizontal" role="form">
						<div>
							<div class="col-sm-12">
								<legend>Timesheet Details
									<?php if ($timesheet->isTimesheetLocked()) {
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
							} else if ($timesheet->istimesheetApproved()) { ?>
							<a href="/timesheets/timesheets.php" class="btn btn-warning pull-left btn-style-left">Add Timesheet</a>
							<?php } ?>
							<a class="btn btn-primary pull-left btn-style-left" href="/timesheets/timesheets.php">To Timesheets</a>
							<?php if (!$timesheet->isTimesheetLocked()) { ?>
							<a class="btn btn-info pull-left btn-style-left" href="/timesheets/schedule-otherEvents.php?timesheet_id=<?php echo $timesheet_id;?>">Edit Schedule</a>
							<?php }
							if ($timesheet->istimesheetApproved() && !$timesheet->istimesheetLocked()) { ?>
							<a class="btn btn-success pull-left btn-style-left" href="/payTimesheets/view-approved-timesheet.php?timesheet_id=<?php echo $timesheet_id;?>&override_approval=true">Unapprove Pay</a>
							<?php } ?>
						</div> <!--end split column-->
						
						<?php include $_SERVER['DOCUMENT_ROOT'].'/timesheets/timesheet-detailsGroup.php'; ?>
					</div><!--form-horizontal-->
				</div> <!--end content column-->
			</div><!--.row-->
			
<!--ALL SCHEDULED TABLE AREA-->

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-12">
						<legend>WH Work</legend>
					</div>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Role</th>
								<th>Hours</th>
								<th>Rate</th>
								<th>Push Rate</th>
								<th>Assoc. Trip</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							
							$other_events = $db->select("SELECT * FROM approvals WHERE `timesheet_id_fk` = :timesheet_id ORDER BY `approval_id` ASC", array( "timesheet_id" => $timesheet_id ));
							if (count($other_events) > 0) {
							
								foreach ($other_events as $row) {
								
									echo '<tr>' . "\n";
								
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n"; //Role
									echo '<td>' . $row['event_hours'] . '</td>' . "\n"; //Hours
									echo '<td>';
									if ($row['event_hours'] != NULL) {
										echo formatMoney($row['other_pay']/$row['event_hours']);
									}
									echo '</td>' . "\n"; //Rate

									$other_total = $row['other_pay'];
									echo '<td>'. formatMoney($row['other_pay']) . ' ' . "\n"; //Push Rate
									if (!empty($row['notes'])) {
										echo '<div class="note_popup" title="Notes" data-placement="left" data-content="' . $row['notes'] . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div></td>' . "\n"; //Info Icon
									}

									echo '<td>';
									if ($row['trip_id_fk']) {
										$trip = new Trip();
										$trip->set_trip_id($row['trip_id_fk']);
										echo $trip->getTripStats();  //Assoc. Trip
									}
									echo'</td>' . "\n";
								
									echo '<td>'. formatMoney($other_total) . '</td>' . "\n"; //Total
								
									$timesheet_total = $timesheet_total + $other_total;
								}
							}
							
							echo '<tr class="total-row">' . "\n";
							echo '<td></td>' . "\n"; //Role
							echo '<td></td>' . "\n"; //Hours
							echo '<td></td>' . "\n"; //Rate
							echo '<td></td>' . "\n"; //Push Rate
							echo '<td>Timesheet Total</td>' . "\n"; //Assoc. Trip
							echo '<td>'. formatMoney($timesheet_total) . '</td>' . "\n"; //Total
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