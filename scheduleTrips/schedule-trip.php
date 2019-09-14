<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

//variable to identify this page title////////
	$title = "Trips"; 

//INITIALIZE ALL VARIABLE///////////
//Necessary because not every page has all variables passed in both GET and POST///

	$trip_id = null;

	// keep track GUIDE validation errors
	$user_id_fkError = null;
	$g_role_id_fkError = null;
	$rigger_boolError = null;
	$food_shopper_boolError = null;
	$adjust_amountError = null;
	$adjust_notesError = null;

	// clear GUIDE data variables
	$user_id_fk = null;
	$g_role_id_fk = null;
	$rigger_bool = null;
	$food_shopper_bool = null;
	$adjust_amount = null;
	$adjust_notes = null;

	// keep track OTHER validation errors
	$tripeventuser_id_fkError = null;
	$o_role_id_fkError = null;
	$tripevent_amountError = null;
	$tripevent_notesError = null;

	// clear OTHER data variables
	$tripeventuser_id_fk = null;
	$o_role_id_fk = null;
	$tripevent_amount = null;
	$tripevent_notes = null;
	
//PROCESS ALL FORMS/////////////

	if (!empty($_POST)) {
		$trip_id = $_REQUEST['trip_id'];
	}
	
//PROCESS ADD GUIDE FORM/////////////
		
	if (!empty($_POST['submit_guide'])) {	

		// keep track post values
		$user_id_fk = $_POST['user_id_fk'];
		$g_role_id_fk = $_POST['g_role_id_fk'];
		$rigger_bool = (empty($_POST['rigger_bool']) ? "N" : ($_POST['rigger_bool']  == 'on' ? "Y" : "N"));
		$food_shopper_bool = (empty($_POST['food_shopper_bool']) ? "N" : ($_POST['food_shopper_bool']  == 'on' ? "Y" : "N"));
		$adjust_amount = (empty($_POST['adjust_amount']) ? 0.00 : $_POST['adjust_amount']);
		$adjust_notes = $_POST['adjust_notes'];

		// validate input
		$valid = true;
		if (empty($user_id_fk)) {
			$user_id_fkError = 'Please enter a name for this Guide';
			$valid = false;
		} else {
			$new_guide = new Guide();
			$new_guide->set_guide_id($user_id_fk);
			$exist = $db->select("SELECT guideevent_id FROM `guide_events` WHERE `user_id_fk` = :user_id_fk AND `trip_id_fk` = :trip_id", 
			array( "user_id_fk" => $user_id_fk,"trip_id" => $trip_id,));
			if (count($exist) > 0) {
				$user_id_fkError = 'This guide is already scheduled on this trip';
				$valid = false;
			} else if($new_guide->getGuidePayrates() == false) {
				$user_id_fkError = 'This guide does not have any active pay rates';
				$valid = false;
			}
		}
		
		if (empty($g_role_id_fk)) {
			$g_role_id_fkError = 'Please enter a role for this Guide';
			$valid = false;
		}

		// insert data
		if ($valid) {		
			$schedule_guide_array = array(
			    'trip_id_fk' => $trip_id,
			    'user_id_fk' => $user_id_fk,
			    'role_id_fk' => $g_role_id_fk,
			    'rigger_bool' => $rigger_bool,
			    'food_shopper_bool' => $food_shopper_bool,
			    'adjust_amount' => $adjust_amount,
			    'adjust_notes' => $adjust_notes,
			    'created_by' => $visitor_id,
				);
			$success = $db->insert('guide_events',$schedule_guide_array);
			
			if (isset($_REQUEST['override_approval'])) {
				if ($_REQUEST['override_approval'] == "true" && $success > 0) {
					$unapproved = unApproveTrip($trip_id, $visitor_id);
				}
			}
		
			header("Location: /scheduleTrips/schedule-trip.php?trip_id=$trip_id");
			exit();
		}
	}

//PROCESS ADD OTHER FORM/////////////
		
	if (!empty($_POST['submit_other'])) {

		// keep track post values
		$tripeventuser_id_fk = $_POST['tripeventuser_id_fk'];
		$o_role_id_fk = $_POST['o_role_id_fk'];
		$tripevent_amount = (empty($_POST['tripevent_amount']) ? 0.00 : $_POST['tripevent_amount']);
		$tripevent_notes = $_POST['tripevent_notes'];
		
		// validate input
		$valid = true;
		if (empty($tripeventuser_id_fk)) {
			$tripeventuser_id_fkError = 'Please enter a name for this Assignee';
			$valid = false;
		}
		if (empty($o_role_id_fk)) {
			$o_role_id_fkError = 'Please enter a role for this Assignee';
			$valid = false;
		}
		if (!empty($tripeventuser_id_fk) && !empty($o_role_id_fk)) {
			$exist = $db->select("SELECT otherevent_id FROM `other_events` WHERE `user_id_fk` = :user_id_fk AND `trip_id_fk` = :trip_id AND `role_id_fk` = :role_id_fk", 
			array( "user_id_fk" => $tripeventuser_id_fk,"trip_id" => $trip_id,"role_id_fk" => $o_role_id_fk,));
			if (count($exist) > 0) {
				$tripeventuser_id_fkError = 'This Assignee and Role have already been scheduled on this trip';
				$valid = false;
			}
		}
		if (is_null($tripevent_amount)) {
			$tripevent_amountError = 'Please enter the dollar amount for this Other Role';
			$valid = false;
		}

		// insert data
		if ($valid) {		
			$schedule_other_array = array(
			    'trip_id_fk' => $trip_id,
			    'user_id_fk' => $tripeventuser_id_fk,
			    'role_id_fk' => $o_role_id_fk,
			    'event_amount' => $tripevent_amount,
			    'event_notes' => $tripevent_notes,
			    'created_by' => $visitor_id,
				);
			$success = $db->insert('other_events',$schedule_other_array);
			
			if (isset($_POST['override_approval'])) {
				if ($_POST['override_approval'] == "true" && $success > 0) {
					$unapproved = unApproveTrip($trip_id, $visitor_id);
				}
			}
			
			header("Location: /scheduleTrips/schedule-trip.php?trip_id=".$trip_id);
			exit();
		}
	}

//GET INFO FOR TRIP DETAILS AREA//////////////////////
	
	if (!empty($_GET['trip_id']) || $_POST['submit_assign'] == "true") {
		$trip_id = $_REQUEST['trip_id'];
	}
//IF TRIP IS LOCKED, REDIRECT TO PAY TRIPS/////////////////

	//INSTANTIATE TRIP///// 
	$trip = new Trip();
	$trip->set_trip_id($trip_id);
	
	if ($trip->isTripLocked()) {
		header("Location: /payTrips/pay-trip.php?trip_id=".$trip_id);
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
?>

<!--TRIP DETAILS AREA-->

			<div class="row">
				<div class="col-sm-12"> <!--start content column-->	    		
					<div class="form-horizontal" role="form">
						<div class="form-actions">	
							<div class="col-sm-12">
								<legend>Trip Details</legend>
							</div>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-primary pull-left btn-style-left" href="/trips/trips.php">Back to Trips</a>
							<a class="btn btn-info pull-left btn-style-left" href="/trips/update-trip.php?trip_id=<?php echo $trip_id;?>">Edit Trip</a>
							<a class="btn btn-success pull-left btn-style-left" href="/payTrips/pay-trip.php?trip_id=<?php echo $trip_id;?>">Review Pay</a>	
						</div> <!--end split column-->
						
						<?php include $_SERVER['DOCUMENT_ROOT'].'/scheduleTrips/trip-detailsGroup.php'; ?>
						
					</div><!--form-horizontal-->
				</div> <!--end content column-->
			</div><!--.row-->
			
			<!--GUIDE TABLE AREA-->

			<div class="row">
				<div class="col-sm-12">	
					<div class="col-sm-12">
						<legend>Guides Work</legend>
					</div>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Guide Name</th>
								<th>Guide Role</th>
								<th>Rigger?</th>
								<th>Shopper?</th>
								<th>Adjustment</th>
								<th>Notes</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$guide_events = $db->select("SELECT * FROM guide_events WHERE `trip_id_fk` = :trip_id ORDER BY guideevent_id ASC", array( "trip_id" => $trip_id ));
							///////INSTANTIATE GUIDE OBJECT///////////
							$guide = new Guide();
							
							if (count($guide_events) > 0) {
								
								$guide->set_guide_id($guide_events[0]['user_id_fk']);
								
								foreach ($guide_events as $row) {
									
									$guide->set_guide_id($row['user_id_fk']);
									
								  	echo '<tr>' . "\n";
									echo '<td>'. $guide->getUserName();
									if ($guide->willCertExpire($trip_id)) { 
										echo '<div class="expired alert-warning">';
										   echo '<strong>Warning!</strong> Certs expired';
										echo '</div>';
									}
									echo '</td>' . "\n";
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
									echo '<td width=100>';
								 	echo ($row['rigger_bool'] == "Y" ? '<span class="glyphicon glyphicon-ok"></span>' : '');
									echo '</td>' . "\n";
									echo '<td>';
								 	echo ($row['food_shopper_bool'] == "Y" ? '<span class="glyphicon glyphicon-ok"></span>' : '');
									echo '</td>' . "\n";
									echo '<td>'. formatMoney($row['adjust_amount']) . '</td>' . "\n";
									echo '<td class="med-notes";>'. format_short_notes($row['adjust_notes']) . '</td>' . "\n";
									echo '<td width=70>' . "\n";
									echo '<div class="btn-group-xs"><a class="btn btn-danger"  href="/scheduleTrips/delete-guideEvent.php?guideevent_id='.$row['guideevent_id'].'&trip_id='.$trip_id.'"><span class="glyphicon glyphicon-remove"></span></a></div>' . "\n";
									echo '</td>' . "\n";
									echo '</tr>' . "\n";
								}
							}
							?>

							<!--ADD GUIDE FORM-->

							<form class="form-horizontal" action="/scheduleTrips/schedule-trip.php" method="post">
								<fieldset>
									<tr>
										<td>
											<div class="form-group <?php echo !empty($user_id_fkError)?'has-error':'';?>">
												<?php
												$names = $guide->getActiveGuides();
												?>
												<select name="user_id_fk" id="select-guide-name" class="form-control" autofocus="autofocus" style="width: 100%;">
													<option value="" default selected>Select a guide</option>
													<?php foreach($names as $name) { ?>
														<option value="<?php echo $name['user_id']; ?>"<?php if($user_id_fk == $name['user_id']) echo ' selected';?>>
															<?php echo htmlentities($name['first_name'] . " " . $name['last_name']); ?>
														</option>
													<?php } ?>
												</select>
												<?php if (!empty($user_id_fkError)): ?>
													<span class="help-inline"><?php echo $user_id_fkError;?></span>
												<?php endif; ?>
											</div>
										</td>	
										<td>
											<div class="form-group <?php echo !empty($g_role_id_fkError)?'has-error':'';?>">
												<select name="g_role_id_fk" id="select-g_role_id_fk" class="form-control">
													<option value="" default selected>Select a role</option>
													<?php foreach(getRoles(1,1) as $key) { ?>
														<option value="<?php echo $key['role_id'] ?>"<?php if($g_role_id_fk == $key['role_id']) echo ' selected';?>>
															<?php echo $key['role_name'] ?>
														</option>
													<?php } ?>
												</select>
												<?php if (!empty($g_role_id_fkError)): ?>
													<span class="help-inline"><?php echo $g_role_id_fkError;?></span>
												<?php endif; ?>
											</div>
										</td>
										<td>
											<div class="form-group <?php echo !empty($rigger_boolError)?'has-error':'';?>">
												<label class="radio-inline">
													<input type="checkbox" name="rigger_bool"<?php if(!empty($rigger_bool) && $rigger_bool == "Y") echo ' checked="checked"';?>>
												</label>
												<?php if (!empty($rigger_boolError)): ?>
													<span class="help-inline"><?php echo $rigger_boolError;?></span>
												<?php endif;?>
											</div>	
										</td>
										<td>
											<div class="form-group <?php echo !empty($food_shopper_boolError)?'has-error':'';?>">
												<label class="radio-inline">
													<input type="checkbox" name="food_shopper_bool"<?php if(!empty($food_shopper_bool) && $food_shopper_bool == "Y") echo ' checked="checked"';?>>
												</label>
												<?php if (!empty($food_shopper_boolError)): ?>
													<span class="help-inline"><?php echo $food_shopper_boolError;?></span>
												<?php endif;?>
											</div>
										</td>
										<td>
											<div class="form-group <?php echo !empty($adjust_amountError)?'has-error':'';?>">
												<input type="number" name="adjust_amount" class="form-control" min="-999" max="999" step="0.01" size="4" placeholder="XX.XX (no dollar sign)" value="<?php echo !empty($adjust_amount)?$adjust_amount:'';?>" title="no dollar sign and no comma(s)">
												<?php if (!empty($adjust_amountError)): ?>
													<span class="help-inline"><?php echo $adjust_amountError;?></span>
												<?php endif;?>
											</div>
										</td>
										<td>
											<div class="form-group <?php echo !empty($adjust_notesError)?'has-error':'';?>">
												<textarea name="adjust_notes" class="form-control" placeholder="250 characters or less" rows="1"><?php echo !empty($adjust_notes)?$adjust_notes:'';?></textarea>
											<?php if (!empty($adjust_notesError)): ?>
												<span class="help-inline"><?php echo $adjust_notesError;?></span>
											<?php endif; ?>
											</div>
										</td>
										<td>
											<div class="form-group btn-group-xs">
												<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-plus"></span></button>
												<?php if ($trip->isTripApproved()) {
													echo '<input type="hidden" name="override_approval" value="true">';
												}?>
												<input type="hidden" name="submit_guide" value="true">
												<input type="hidden" name="submit_assign" value="true">
												<input type="hidden" name="trip_id" value="<?php echo $trip_id; ?>">
											</div>	
										</td>
									</tr>
								</fieldset>
							</form>
						</tbody>
					</table>	
				</div>
			</div><!--row-->
			
			<!--OTHERS TABLE AREA-->

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-12">
						<legend>Trip Work</legend>
					</div>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Other Name</th>
								<th>Other Role</th>
								<th>Amount</th>
								<th>Notes</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$other_events = $db->select("SELECT * FROM other_events WHERE `trip_id_fk` = :trip_id AND `timesheet_id_fk` IS NULL ORDER BY otherevent_id ASC", array( "trip_id" => $trip_id ));
								
							///////INSTANTIATE USER OBJECT///////////
							$user = new User();
							
							if (count($other_events) > 0) {
								foreach ($other_events as $row) {
									
									$user->set_user_id($row['user_id_fk']);
								
									echo '<tr>' . "\n";
									echo '<td>'. $user->getUserName() . '</td>' . "\n";
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
									echo '<td>'. formatMoney($row['event_amount']) . '</td>' . "\n";
									echo '<td>'. format_short_notes($row['event_notes']) . '</td>' . "\n";
									echo '<td width=70>' . "\n";

									if ($row['timesheet_id_fk']) {
										echo '<div class="btn-group-xs"><a class="btn btn-success"  href="/payTimesheets/pay-timesheet.php?timesheet_id='.$row['timesheet_id_fk'].'"><span class="glyphicon glyphicon-share-alt"></span></a></div>' . "\n";
									} else {
										echo '<div class="btn-group-xs"><a class="btn btn-danger"  href="/scheduleTrips/delete-tripEvent.php?tripevent_id='.$row['otherevent_id'].'&trip_id='.$trip_id.'"><span class="glyphicon glyphicon-remove"></span></a></div>' . "\n";
									}
									echo '</td>' . "\n";
									echo '</tr>' . "\n";
								}
							}
							?>

							<!--ADD OTHER FORM-->

							<form class="form-horizontal" action="/scheduleTrips/schedule-trip.php" method="post">
								<fieldset>
									<tr>
										<td>
											<div class="form-group <?php echo !empty($tripeventuser_id_fkError)?'has-error':'';?>">
												<?php
												$others = $user->getActiveUsers();
												?>
												<select name="tripeventuser_id_fk" id="select-guide-name" class="form-control" style="width: 100%;">
													<option value="" default selected>Select a name</option>
													<?php foreach($others as $other) { ?>
														<option value="<?php echo $other['user_id']; ?>"<?php if($tripeventuser_id_fk == $other['user_id']) echo ' selected';?>>
															<?php echo htmlentities($other['name']); ?>
														</option>
													<?php } ?>
												</select>
												<?php if (!empty($tripeventuser_id_fkError)): ?>
													<span class="help-inline"><?php echo $tripeventuser_id_fkError;?></span>
												<?php endif; ?>
											</div>
										</td>
										<td>
											<div class="form-group <?php echo !empty($o_role_id_fkError)?'has-error':'';?>">
												<select name="o_role_id_fk" id="select-role" class="form-control" style="width: 100%;">
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
											<div class="form-group <?php echo !empty($tripevent_amountError)?'has-error':'';?>">
												<input type="number" id="amount-target" name="tripevent_amount" class="form-control" min="-999" max="999" step="0.01" size="4" placeholder="XX.XX (no dollar sign)" value="<?php echo !empty($tripevent_amount)?$tripevent_amount:'';?>" title="no dollar sign and no comma(s)">
												<?php if (!empty($tripevent_amountError)): ?>
													<span class="help-inline"><?php echo $tripevent_amountError;?></span>
												<?php endif;?>
											</div>
										</td>
										<td>
											<div class="form-group <?php echo !empty($tripevent_notesError)?'has-error':'';?>">
												<textarea name="tripevent_notes" class="form-control" placeholder="250 characters or less" rows="1"><?php echo !empty($tripevent_notes)?$tripevent_notes:'';?></textarea>
											<?php if (!empty($tripevent_notesError)): ?>
												<span class="help-inline"><?php echo $tripevent_notesError;?></span>
											<?php endif; ?>
											</div>
										</td>
										<td>
											<div class="form-group btn-group-xs">
												<button type="submit" class="btn btn-warning smallbtn"><span class="glyphicon glyphicon-plus"></span></button>
												<?php if ($trip->isTripApproved()) {
													echo '<input type="hidden" name="override_approval" value="true">';
												}?>
												<input type="hidden" name="submit_other" value="true">
												<input type="hidden" name="submit_assign" value="true">
												<input type="hidden" name="trip_id" value="<?php echo $trip_id; ?>">
											</div>	
										</td>
									</tr>
								</fieldset>
							</form>
						</tbody>
					</table>
				</div>
			</div><!--row-->

			<!--TIMESHEET TABLE AREA-->
			
			<?php 
			///////GET TIMESHEET EVENTS////////////
			$other_events = $db->select("SELECT * FROM other_events WHERE `trip_id_fk` = :trip_id AND `timesheet_id_fk` IS NOT NULL ORDER BY otherevent_id ASC", array( "trip_id" => $trip_id ));

			///////INSTANTIATE USER OBJECT///////////
			$user = new User();
			if (count($other_events) > 0) {
			?>
				<div class="row">
					<div class="col-sm-12">
						<div class="col-sm-12">
							<legend>Timesheet Work (approve in timesheet)</legend>
						</div>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Other Name</th>
									<th>Other Role</th>
									<th>Amount</th>
									<th>Notes</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach ($other_events as $row) {
									$user->set_user_id($row['user_id_fk']);
								
									echo '<tr>' . "\n";
									echo '<td>'. $user->getUserName() . '</td>' . "\n";
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
									echo '<td>'. formatMoney($row['event_amount']) . '</td>' . "\n";
									echo '<td>'. format_short_notes($row['event_notes']) . '</td>' . "\n";
									echo '<td width=70>' . "\n";
									echo '<div class="btn-group-xs"><a class="btn btn-success"  href="/payTimesheets/pay-timesheet.php?target=Return to Trip&timesheet_id='.$row['timesheet_id_fk'].'"><span class="glyphicon glyphicon-share-alt"></span></a></div>' . "\n";
									echo '</td>' . "\n";
									echo '</tr>' . "\n";
								}
								?>
							</tbody>
						</table>
					</div>
				</div><!--row-->
			<?php
			}		

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
	?>

	<script type="text/javascript">
		$( document ).ready(function() {
		  $('#select-role').change(function() {
      	var selected_id = $(this).val();
      	var role_rate = <?php echo json_encode($get_roles); ?>; //?
		    var i = null;
		    for (i = 0; role_rate.length > i; i += 1) {
		        if (role_rate[i].role_id === selected_id) {
		            var value = role_rate[i].default_amount;
		        }
		    }
      	$('#amount-target').val(value);
      }); // end role change

		  $('#select-guide-name').change(function() {
      	$('#select-g_role_id_fk').val(11);
      }); // end guide name change

		}); //end ready
	</script>

	</body>
</html>