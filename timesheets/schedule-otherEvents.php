<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

//variable to identify this page title////////
	$title = "Timesheet"; 

//INITIALIZE ALL VARIABLE///////////
//Necessary because not every page has all variables passed in both GET and POST///

	//keep track OTHER validation errors
	$schTrips_idError = null;
	$o_role_id_fkError = null;
	$hoursError = null;
	$whevent_amountError = null;
	$whevent_notesError = null;
	$event_dateError = null;

	//clear OTHER data variables
	$default_hourly_rate = DEFAULT_WH_RATE;
	$timesheet_id = 0;
	$user_id_fk = null;
	$otherevent_id = null;
	$schTrips_id = null;
	$o_role_id_fk = null;
	$hours = null;
	$whevent_amount = null;
	$whevent_notes = null;
	$event_date = null;
	
//PROCESS ALL FORMS/////////////
	
	if (!empty($_GET['timesheet_id']) || !empty($_POST)) {
		$timesheet_id = $_REQUEST['timesheet_id'];
	} else {
		header("Location: /timesheets/timesheets.php");	
		exit();
	}

	//INSTANTIATE TIMESHEET///// 
	$timesheet = new Timesheet();
 	$timesheet->set_sheet_id($timesheet_id);
	
// //PROCESS ADD OTHER FORM/////////////
		
	if (!empty($_POST['submit_other'])) {

		// keep track post values
		$user_id_fk = $_POST['user_id_fk'];
		$timesheet_id = $_POST['timesheet_id'];
		$schTrips_id = $_POST['schTrips_id'];
		$o_role_id_fk = $_POST['o_role_id_fk'];
		$hours = $_POST['hours'];
		$whevent_amount = $_POST['whevent_amount'];
		$event_date = $_POST['event_date'];
		$whevent_notes = $_POST['whevent_notes'];
		
		// validate input
		$valid = true;

		if (empty($o_role_id_fk)) {
			$o_role_id_fkError = 'Please enter a role for this Event';
			$valid = false;
		}

		// if (empty($hours)) {
		// 	$hours = null;
		// }

		if (empty($whevent_amount)) {
			$whevent_amountError = 'Please enter the dollar amount for this Event';
			$valid = false;
		}

		if (empty($event_date)) {
			$event_dateError = 'Please enter the date for this Event';
			$valid = false;
		}

		// insert data
		if ($valid) {
			$schedule_other_array = array(
			  "user_id_fk" => "$user_id_fk",
				"timesheet_id_fk" => "$timesheet_id",
		    "role_id_fk" => "$o_role_id_fk",
		    "event_amount" => "$whevent_amount",
		    "event_notes" => "$whevent_notes",
		    "event_date" => "$event_date",
		    "created_by" => "$visitor_id",
				);
			if ($hours != NULL) {		
				$schedule_other_array['event_hours'] = $hours;
			}
			if ($schTrips_id) {		
				$schedule_other_array['trip_id_fk'] = $schTrips_id;
			}

			///INSERT OTHER EVENT////////
			$success = $db->insert('other_events', $schedule_other_array);
			
			/////IF IT IS APPROVED, UNAPPROVE////////////
			$timesheet = new Timesheet();
			$timesheet->set_sheet_id($timesheet_id);
			if ($timesheet->isTimesheetApproved()) {
				if ($success > 0) {
					$unapproved = unApproveTimesheet($timesheet_id, $visitor_id);
				}
			}
			
			header("Location: /timesheets/schedule-otherEvents.php?timesheet_id=".$timesheet_id);
			exit();
				
		}
	}

//IF TIMESHEET IS LOCKED, REDIRECT TO PAY TIMESHEETS/////////////////

	if ($timesheet->isTimesheetLocked()) {
		header("Location: /payTimesheets/pay-timesheet.php?timesheet_id=".$timesheet_id);
		exit();
	}
	
	if ( null==$timesheet_id) {
		header("Location: /timesheets/timesheets.php");
		exit();
	} else {
		
		$data = $timesheet->getTimesheetDetails();

		$user_id_fk = $timesheet->getSheetUser();
		$user = new User();
		$user->set_user_id($user_id_fk);
		$trip = new Trip();
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

<!--TIMESHEET DETAILS AREA-->

			<div class="row">
				<div class="col-sm-12"> <!--start content column-->	    		
					<div class="form-horizontal" role="form">
						<div class="form-actions">	
							<div class="col-sm-12">
								<legend>Timesheet Details</legend>
							</div>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-primary pull-left btn-style-left" href="/timesheets/timesheets.php">to Timesheets</a>
							<a class="btn btn-success pull-left btn-style-left" href="/payTimesheets/pay-timesheet.php?timesheet_id=<?php echo $timesheet_id;?>">Review Pay</a>	
						</div> <!--end split column-->
						
						<?php include $_SERVER['DOCUMENT_ROOT'].'/timesheets/timesheet-detailsGroup.php'; ?>
						
					</div><!--form-horizontal-->
				</div> <!--end content column-->
			</div><!--.row-->

			<!--WH WORK TABLE AREA-->

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-12">
						<legend>Warehouse Work</legend>
					</div>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Role</th>
								<th>Hours</th>
								<th>Amount</th>
								<th>Notes</th>
								<th>Date</th>
								<th>Assoc. Trip</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$other_events = $db->select("SELECT * FROM other_events WHERE `timesheet_id_fk` = :timesheet_id ORDER BY 'event_date' ASC", array( "timesheet_id" => $timesheet_id ));
							
							if (count($other_events) > 0) {
								foreach ($other_events as $row) {
									
									$trip->set_trip_id($row['trip_id_fk']);
								
									echo '<tr>' . "\n";
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n"; //Role
									echo '<td>'. $row['event_hours'] . '</td>' . "\n"; //Hours
									echo '<td>'. formatMoney($row['event_amount']) . '</td>' . "\n"; //Amount
									echo '<td>'. format_short_notes($row['event_notes']) . '</td>' . "\n"; //Notes
									echo '<td>'; 
									echo $row['event_date'] == null?"":format_date($row['event_date']);
									echo '</td>' . "\n"; //Event Date
									echo '<td>';
									$t_trip = $trip->getTripStats();
									echo $t_trip == "Error"?"":$t_trip;
									echo '</td>' . "\n"; //Associated Trip
									echo '<td width=80>' . "\n";
									echo '<div class="btn-group btn-group-xs">' . "\n";
									echo '<a class="btn btn-danger"  href="/timesheets/delete-otherEvent.php?otherevent_id='.$row['otherevent_id'].'&timesheet_id='.$row['timesheet_id_fk'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
									if (!$row['trip_id_fk'] == NULL) {
										echo '<a class="btn btn-success"  href="/payTrips/pay-trip.php?trip_id='.$row['trip_id_fk'].'"><span class="glyphicon glyphicon-share-alt"></span></a>' . "\n";
									}
									echo '</div>' . "\n";
									echo '</td>' . "\n";
									echo '</tr>' . "\n";
									
								}
							}
							?>

							<!--ADD OTHER FORM-->

							<form class="form-horizontal" action="/timesheets/schedule-otherEvents.php" method="post">
								<fieldset>
									<tr>
										<td>
											<div class="form-group <?php echo !empty($o_role_id_fkError)?'has-error':'';?>">
												<select name="o_role_id_fk" id="select-role" class="form-control" style="width: 100%;" autofocus>
													<option value="" default selected>Select a role</option>
													<?php 
													$get_roles = getRoles(2,2); //variable to pass to js
													foreach($get_roles as $key) { 
													?>
														<option value="<?php echo $key['role_id'] ?>"<?php if($o_role_id_fk == $key['role_id']) echo ' selected';?>>
															<?php echo $key['role_name'] ?>
														</option>
													<?php } ?>
												</select>
												<?php if (!empty($o_role_id_fkError)): ?>
													<span class="help-inline"><?php echo $o_role_id_fkError;?></span>
												<?php endif; ?>
											</div>
										</td>
										<td>
											<div class="form-group hours-cell <?php echo !empty($hoursError)?'has-error':'';?>">
												<input type="number" id="hours-input" name="hours" class="form-control hours-input" min="-999" max="999" step="0.01" value="<?php echo !empty($hours)?$hours:'';?>" title="enter number of hours">
												&nbsp;x&nbsp;$<input type="number" id="rate-input" name="rate" class="form-control hours-input rate-input" min="-999" max="999" step="0.01" placeholder="XX.X" value="<?php echo number_format($default_hourly_rate, 2);?>" title="adjust the default rate">
												<?php if (!empty($hoursError)): ?>
													<span class="help-inline"><?php echo $hoursError;?></span>
												<?php endif;?>
											</div>
										</td>
										<td>
											<div class="form-group <?php echo !empty($whevent_amountError)?'has-error':'';?>">
												<input type="number" id="amount-target" name="whevent_amount" class="form-control" min="-9999" max="9999" step="0.01" placeholder="XX.XX" value="<?php echo !empty($whevent_amount)?$whevent_amount:'';?>" title="no dollar sign and no comma(s)">
												<?php if (!empty($whevent_amountError)): ?>
													<span class="help-inline"><?php echo $whevent_amountError;?></span>
												<?php endif;?>
											</div>
										</td>
										<td>
											<div class="form-group <?php echo !empty($whevent_notesError)?'has-error':'';?>">
												<textarea name="whevent_notes" class="form-control" placeholder="250 characters or less" rows="1"><?php echo !empty($whevent_notes)?$whevent_notes:'';?></textarea>
											<?php if (!empty($whevent_notesError)): ?>
												<span class="help-inline"><?php echo $whevent_notesError;?></span>
											<?php endif; ?>
											</div>
										</td>
										<td>
											<div class="form-group <?php echo !empty($event_dateError)?'has-error':'';?>">
												<input name="event_date" id="eventDate" type="date" class="form-control" placeholder="Event Date" value="<?php echo !empty($event_date)?$event_date:'';?>">
												<?php if (!empty($event_dateError)): ?>
													<span class="help-inline"><?php echo $event_dateError;?></span>
												<?php endif;?>
											</div>
										</td>
										
										<td>
											<div class="form-group <?php echo !empty($schTrips_idError)?'has-error':'';?>">
												<?php
												$schTrips = $trip->getScheduledTrips();
												?>
												<select name="schTrips_id" id="select-sch-trips" class="form-control" style="width: 100%;">
													<option value="" default selected>Select a trip (optional)</option>
													<?php foreach($schTrips as $r) { ?>
														<option value="<?php echo $r['trip_id']; ?>"<?php if($schTrips_id == $r['trip_id']) echo ' selected';?>>
															<?php echo htmlentities(format_short_date($r['putin_date']) . " " . $r['rivertrip_name'] . " " . $r['triptype_name']); ?>
														</option>
													<?php } ?>
												</select>
												<?php if (!empty($schTrips_idError)): ?>
													<span class="help-inline"><?php echo $schTrips_idError;?></span>
												<?php endif; ?>
											</div>
										</td>
										
										<td>
											<div class="form-group btn-group-xs">
												<button type="submit" class="btn btn-warning smallbtn"><span class="glyphicon glyphicon-plus"></span></button>
												<input type="hidden" name="submit_other" value="true">
												<input type="hidden" name="user_id_fk" value="<?php echo $user_id_fk; ?>">
												<input type="hidden" name="timesheet_id" value="<?php echo $timesheet_id; ?>">
											</div>	
										</td>
									</tr>
								</fieldset>
							</form>
						</tbody>
					</table>
				</div>
			</div><!--row-->

	<?php
		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
	?>

	<script type="text/javascript">
		var Defaults = {};
		$( document ).ready(function() {
		  $('#select-role').change(function() {
      	var selected_id = $(this).val();
      	var role_rate = <?php echo json_encode($get_roles); ?>;
		    var i = null;
		    for (i = 0; role_rate.length > i; i += 1) {
		        if (role_rate[i].role_id === selected_id) {
		            var value = role_rate[i].default_amount;
		            Defaults.amount = value;
		        }
		    }
      	$('#amount-target').val(value);
      }); // end change

      $('#select-role').blur(function() {
      // 	var role_rate = <?php echo json_encode($get_roles); ?>;
		    // var value = role_rate[i].default_amount;
		    if (Defaults.amount != 0) {
  				//document.getElementById('#amount-target').focus();
  				$('#amount-target').focus();
       	}
      }); // end blur

     	$('#hours-input').blur(function() {
       	var hours = $(this).val();
       	var rate = $('#rate-input').val();
       	var total = hours*rate;
      	$('#amount-target').val(total.toFixed(2));
  			$('#amount-target').focus();
      }); // end blur

     	$('#rate-input').blur(function() {
       	var rate = $(this).val();
       	var hours = $('#hours-input').val();
       	var total = hours*rate;
      	$('#amount-target').val(total.toFixed(2));
      }); // end blur
		}); //end ready
	</script>

	</body>
</html>