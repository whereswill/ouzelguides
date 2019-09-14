<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Approvals";

//INITIALIZE ALL VARIABLE///////////
//Necessary because not every page has all variables passed in both GET and POST///
	
	//initialize timesheet variable
	$timesheet_id = 0;

	//keep track of validation errors
	$end_dateError = null;

	//clear data variables
	$end_date = null;
	$modal_show = false;

	///////INSTANTIATE OBJECTS////////
	$trip = new Trip();
	$timesheet = new Timesheet();

	////PROCESS DATE/////////////
	if (!empty($_POST)) {

		// keep track post values
		$end_date = $_POST['end_date'];
		
		// validate input
		$valid = true;
		if (empty($end_date)) {
			$end_dateError = 'Please enter the end date for this Pay Period';
			$valid = false;
		}

		if ($valid == true) {
			$unapproved_trips = $trip->areUnapproved($end_date);
			$unapproved_timesheets = $timesheet->areUnapproved($end_date);
			if (count($trip->getApprovedTripsBeforeEnd($end_date)) == 0 && count($timesheet->getApprovedTimesheetsBeforeEnd($end_date)) == 0) {
				$modal_show = 1;
			} else if ($unapproved_trips || $unapproved_timesheets) {
				$modal_show = 2;
			} else {
				header("Location: /approveWork/view-statements.php?end_date=$end_date");
				exit();	
			}
		}
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">UnApproved Work</h3>
						<p>Please select an end date for the pay period you would like to pay</p>

						<form class="form-horizontal" action="/approveWork/approve-work.php" method="post">
							<fieldset>
								<div class="form-group col-sm-4 <?php echo !empty($end_dateError)?'has-error':'';?>">
									<input name="end_date" id="end_date" type="date" class="form-control" placeholder="End Date" value="<?php echo !empty($end_date)?$end_date:'';?>">
									<?php if (!empty($end_dateError)): ?>
										<span class="help-inline"><?php echo $end_dateError;?></span>
									<?php endif;?>
								</div>
								<div class="form-group col-sm-8">
									<button type="submit" class="btn btn-primary marginL"> View Statements</button>

									<!-- Update Modal for warning against unapproved trips-->
									<?php 
									if ($modal_show == 1) { 
									?>
										<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										  <div class="modal-dialog">
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
										        <h4 class="modal-title" id="myModalLabel">There are no approved Pay Events</h4>
										      </div>
										      <div class="modal-body">
										        <p>There are no approved Trips or Timesheets on or before the selected Pay Period end date.</p>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
										      </div>
										    </div>
										  </div>
										</div> 
									<?php 
									} else if ($modal_show == 2) { 
									?>
										<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										  <div class="modal-dialog">
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
										        <h4 class="modal-title" id="myModalLabel">There are Unapproved Trips or Timesheets</h4>
										      </div>
										      <div class="modal-body">
										      	<?php
										      	if (isset($unapproved_trips) && $unapproved_trips <> false) {
										      		echo '<p>There are ' . $unapproved_trips . ' unapproved trips with a take-out date before the end date<br />';
										      	}
										      	if (isset($unapproved_timesheets) && $unapproved_timesheets <> false) {
										      		echo '<p>There are ' . $unapproved_timesheets . ' unapproved timesheets with events before the end date<br />';
										      	}
										      	?> 
										        <p>Proceeding will exclude any unapproved events from being paid. unApproved trips or timesheets will continue to appear for future pay periods. Do you still want to proceed?</p>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-danger" data-dismiss="modal">Go Back & Approve</button>
										        <a class="btn btn-success" href="/approveWork/view-statements.php?end_date=<?php echo $end_date;?>">Proceed</a>	
										      </div>
										    </div>
										  </div>
										</div> 
									<?php 
									} 
									?>
									<!-- end modal -->

								</div>	
							</fieldset>
						</form>
					</div>
					<div>
						<table class="table table-striped table-bordered" id="sorted_table_3">
							<thead>
								<tr>
                	<th>Type</th>
									<th>Worksheet Name</th>
									<th style="display:none;">Sort Dates</th>
									<th>Event Dates</th>
									<th>Assigned</th>
									<th>Approved</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 

									$trips = $db->select("SELECT `trip_id`, `approved_on`, `putin_date` FROM `trips` WHERE `locked_on` IS NULL");
									foreach ($trips as $row) {
										//Set trip id///// 
										$trip->set_trip_id($row['trip_id']);
										//$sort_date = DateTime::createFromFormat("Y-m-d", $row['putin_date']);
										
									  	echo '<tr>' . "\n";
                    	echo '<td class="text-center"><span class="icon-user glyphicon glyphicon-road"></span></td>' . "\n"; //Type
									   	echo '<td>'. $trip->getTripNameType() . '</td>' . "\n";
									   	echo '<td style="display:none;">' . $trip->getSortDate() . '</td>' . "\n";
									   	echo '<td>'. $trip->getTripDates() . '</td>' . "\n";
									   	echo '<td>' . $trip->numberOfAssigned() . ' assigned</td>' . "\n";
									   	echo '<td>';
								 		if(!is_null($row['approved_on'])) {
											echo format_date($row['approved_on']);
										} else {
											echo  '<span class="glyphicon glyphicon-remove icon-danger"></span>';
										}
										echo '</td>' . "\n";
									  echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
									  echo '<a class="btn btn-success"  href="/payTrips/pay-trip.php?target=Return to Pay&trip_id='.$row['trip_id'].'"><span class="glyphicon glyphicon-share-alt"></span></a>' . "\n";
										echo '</div>' . "\n";
								   	echo '</td>' . "\n";
								   	echo '</tr>' . "\n";
									}

									$timesheets = $db->select("SELECT `timesheet_id`, `approved_on` FROM `timesheets` WHERE `locked_on` IS NULL");
									foreach ($timesheets as $row) {
										//set timesheet id///// 
										$timesheet->set_sheet_id($row['timesheet_id']);
										$sheetUser = $timesheet->getSheetUser();
										//$sort_date = DateTime::createFromFormat("Y-m-d", $timesheet->getSortDate());
										$user = new User();
										$user->set_user_id($sheetUser);
										
									  	echo '<tr>' . "\n";
                    	echo '<td class="text-center"><span class="icon-user glyphicon glyphicon-list-alt"></span></td>' . "\n"; //Type
									   	echo '<td>'. $user->getUserName() . '</td>' . "\n";
									   	echo '<td style="display:none;">' . $timesheet->getSortDate() . '</td>' . "\n";
									   	echo '<td>'. $timesheet->getTimesheetDates() . '</td>' . "\n";
									   	echo '<td>'. $timesheet->getSheetCount() . ' events</td>' . "\n";
									   	echo '<td>';
								 		if(!is_null($row['approved_on'])) {
											echo format_date($row['approved_on']);
										} else {
											echo  '<span class="glyphicon glyphicon-remove icon-danger"></span>';
										}
										echo '</td>' . "\n";
									  echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
										echo '<a class="btn btn-success"  href="/payTimesheets/pay-timesheet.php?target=Approvals&timesheet_id='.$row['timesheet_id'].'"><span class="glyphicon glyphicon-share-alt"></span></a>' . "\n";
										echo '</div>' . "\n";
								   	echo '</td>' . "\n";
								   	echo '</tr>' . "\n";
									}
								?>
							</tbody>
						</table>
					</div> <!-- .row -->
				</div> <!--end content column-->
			</div> <!-- .row -->
<?php
	if ($modal_show) {
		?><script> $('#approvalModal').modal('show');</script><?php
	}
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>