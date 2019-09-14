<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Pay Sheets";

	//GET PAY PERIOD END DATE///////////////
	
	if (isset($_GET['end_date']) && isset($_GET['lock_date'])) {
		$end_date = $_GET['end_date'];
		$lock_date = $_GET['lock_date'];
	} else {
		header("Location: /approveWork/approve-work.php");
		exit();
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	?>

			<div class="row hidden-print">
				<div class="col-sm-12"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">View Pay Period</h3>
						<p class="currentR">This pay period is locked from further updates.</p>
						<button class="btn btn-primary" onClick="window.print()"> Print Report</button>
					</div>
				</div>
			</div><!--row-->

			<?php
			/////Instantiate objects/////////
			$guide = new Guide;
			$trip = new Trip();
			$timesheet = new Timesheet();

			///Get list of users on approved trips and timesheets
			$locked_users = $guide->getLockedUsers($lock_date);

			$locked_query = "SELECT * FROM `approvals` 
											 WHERE `locked` = :end_date 
											 AND `user_id_fk` = :user_id
											 ORDER BY `event_date` ASC";
			?>

<!-- PRINT SUMMARY SECTION -->

			<div class="row new-page">
				<div class="col-sm-12"> <!-- start content column -->
					<div class="text-center">
						<h4>Ouzel Outfitters Pay Period Summary</h4>
						<p>for Pay Period ending: <?php echo format_date($end_date);?></p><br />
					</div>
					<table class="table table-print">
						<thead>
							<tr>
								<th>Name</th>
								<th>Base Rate</th>
								<th>River Days</th>
								<th>Bonus this Check</th>
								<th>Trip Pay</th>
								<th>Non-trip Pay</th>
								<th>QB Hours</th> <!-- Remove for hours -->
								<th>Total this Paycheck</th>
							</tr>
						</thead>
						<tbody>
							<?php 	
							$trips_to_lock = [];
							$timesheets_to_lock = [];
							$total_river_days = 0;
							$total_period_bonus = 0;
							$total_trip_pay = 0;
							$total_non_trip_pay = 0;
							$total_non_trip_hours = 0; //Remove for hours
							$total_period_pay = 0;

							foreach ($locked_users as $key => $locked_user) {

								$guide->set_guide_id($locked_user['user_id_fk']);	

								$locked = $db->select($locked_query, array( "end_date" => $lock_date, "user_id" => $locked_user['user_id_fk']));
								$period_bonus = 0;
								$period_pay = 0;
								$river_days = 0;
								$trip_pay = 0;
								$non_trip_pay = 0;
								$non_trip_hours = 0; //Remove for hours

								////Print out row for each event
								foreach ($locked as $row) {

									$print = false;
									if ($row['trip_id_fk'] && !$row['timesheet_id_fk']) {
										/////Set object ID//////
										$trip->set_trip_id($row['trip_id_fk']);
										//Find out if it is approved
										if ($trip->isApprovedTripBeforeEnd($end_date)) {
											//Set print flag
											$print = true;
											//Load array of trips to lock
											$trips_to_lock[] = $row['trip_id_fk'];
											//Get number of river days
											if ($row['guideevent_id_fk']) {
												$trip_days = $trip->riverDays($row['trip_id_fk'], $row['user_id_fk']);
									  		$river_days += $trip_days;
								  		}
										}
									} else {
										/////Set object ID//////
										$timesheet->set_sheet_id($row['timesheet_id_fk']);
										if ($timesheet->isApprovedTimesheetBeforeEnd($end_date)) {
											//Set print flag
											$print = true;
											//Load array of timesheets to lock
											$timesheets_to_lock[]= $row['timesheet_id_fk'];
										}
									}
									if ($print) {
							   		$row_total = $row['base_pay']; //Base Pay
							   		$row_total += $row['tl_pay']; //TL Pay
							   		$row_total += $row['sat_pay']; //Sat Pay
							   		$row_total += $row['bump_pay']; //Bump Pay
							   		$row_total += $row['rig_pay']; //Rig Pay
							   		$row_total += $row['shop_pay']; //Shop Pay
							   		$row_total += $row['other_pay']; //Other Pay
							   		$row_total += $row['cert_pay']; //Cert. Pay
							   		$period_pay += $row_total; //Total
							   		$period_bonus += $row['bonus_pay']; //Bonus Pay

							   		if ($row['trip_id_fk']) {
							   			$trip_pay += $row_total;
								  	} else {
							   			$non_trip_pay += $row_total;
								  	}
									} //End if print
								} //End for each

								////Print out total row for each guide
								echo '<tr>' . "\n";
								echo '<td>' . $guide->getUserName() . '</td>' . "\n"; //Name
								echo '<td>' . $guide->getCurrentPayrate() . '</td>' . "\n"; //Base Rate
								echo '<td>' . $river_days . '</td>' . "\n"; //River Days
								$total_river_days += $river_days;
						   	echo '<td>'. formatMoney($period_bonus) . '</td>' . "\n"; //Bonus Total
								$total_period_bonus += $period_bonus;
								echo '<td>' . formatMoney($trip_pay) . '</td>' . "\n"; //Trip Pay
								$total_trip_pay += $trip_pay;
								echo '<td>' . formatMoney($non_trip_pay) . '</td>' . "\n"; //Non-trip Pay
								$total_non_trip_pay += $non_trip_pay;
								$non_trip_hours = round($non_trip_pay/DEFAULT_WH_RATE,2); //Remove for hours
								echo '<td>' . $non_trip_hours . '</td>' . "\n"; //Non-trip Hours Remove for hours
								$total_non_trip_hours += $non_trip_hours; //Remove for hours
								echo '<td>'. formatMoney($period_pay) . '</td>' . "\n"; //Total Pay
								$total_period_pay += $period_pay;
						   	echo '</tr>' . "\n";
								
							} //End for each
							////Print out total row for pay period
							echo '<tr">' . "\n";
							echo '<td></td>' . "\n"; //Name
							echo '<td></td>' . "\n"; //Base Rate
							echo '<td><strong>' . $total_river_days . '</strong></td>' . "\n"; //River Days
					   	echo '<td><strong>'. formatMoney($total_period_bonus) . '</strong></td>' . "\n"; //Bonus Total
							echo '<td><strong>' . formatMoney($total_trip_pay) . '</strong></td>' . "\n"; //Trip Pay
							echo '<td><strong>' . formatMoney($total_non_trip_pay) . '</strong></td>' . "\n"; //Non-trip Pay
							echo '<td><strong>' . $total_non_trip_hours . '</strong></td>' . "\n"; //Non-trip Hours - Remove for Hours
							echo '<td><strong>'. formatMoney($total_period_pay) . '</strong></td>' . "\n"; //Total Pay
					   	echo '</tr>' . "\n";

							?>
						</tbody>
					</table>
				</div> <!--end content column-->
				<?php
				///Remove duplicate ids from lock variables and reset keys
				$trips_to_lock = array_values(array_unique($trips_to_lock, SORT_REGULAR));
				$timesheets_to_lock = array_values(array_unique($timesheets_to_lock, SORT_REGULAR));

				?>
			</div> <!-- .row -->
			<div class="row new-page">
				<div class="col-xs-4">
					<h4>Locked Trips</h4>
					<ul>
					<?php
						foreach ($trips_to_lock as $key => $ttl) {
							$trip->set_trip_id($ttl);
							echo "<li>" . $trip->getTripStats() . "</li>";
						}
					?>
				</ul>
				</div>
				<div class="col-xs-4">
					<h4>Locked Timesheets</h4>
					<ul>
					<?php
						foreach ($timesheets_to_lock as $key => $tstl) {
							$timesheet->set_sheet_id($tstl);
							$guide->set_guide_id($timesheet->getSheetUser());
							echo "<li>" . $guide->getUserName() . " " . $timesheet->getTimesheetDates() .  "</li>";
						}
					?>
				</ul>
				</div>
			</div><!-- .row -->
			<div class="row hidden-print">
				<div class="col-sm-4"></div>
				<div class="col-sm-4">
					<hr class="page-line">
				</div>
			</div><!-- .row -->
			<?php

//////PRINT STATEMENT FOR EACH USER//////

			foreach ($locked_users as $locked_user) {

				/////Set object ID//////
				$guide->set_guide_id($locked_user['user_id_fk']);

				echo '<div class="row new-page">';
					echo '<div class="col-sm-12">'; ///start content column	
						echo '<h4>' . $guide->getUserName(); //Name
						echo '<span class="pull-right">Pay Period ending: ' . format_date($end_date) . '</span></h4>'; //Pay period date
					echo '</div>';

					if ($guide->isActiveGuide()) {
						echo '<div class="col-sm-12">'; ///start content column	
							echo '<hr class="hr-print">';
						echo '</div>';

						echo '<div class="col-xs-4 print_group">';
							echo '<p>Base Rate: ' . $guide->getCurrentPayrate() . '</p>'; //Base Rate
							echo '<p>First Aid Pay: ' . formatMoney($guide->getFAPay()) . '</p>'; //FA Bonus Amount
							$other_certs = $guide->getOtherCertPay();
							if ($other_certs) {
								foreach ($other_certs as $other_cert) {
									echo '<p>' . $other_cert['certrate_name'] . ' Pay: ' . formatMoney($other_cert['cert_amount']) . '</p>'; //FA Bonus Amount
								}
							}
						echo '</div>';

						echo '<div class="col-xs-4 print_group">'; //Middle column -->
								if ($guide->isBonusEligible()) {
									echo '<p>Bonus Eligible? Yes</p>'; //Bonus Eligible
									echo '<p>Bonus per Day: ' . formatMoney($guide->getBonusPerDay()) . '</p>'; //Bonus Amount
									echo '<p>Current Bonus Account: ' . formatMoney($guide->getCurrentBonus()) . '</p>'; //Bonus Amount
								} else {
									echo '<p>Bonus Eligible? No</p>'; //Bonus Eligible
								}
						echo '</div>';
						echo '<div class="col-xs-4 print_group">'; //Right column -->
						echo '</div>';
					}
					?>
					<div class="col-sm-12">
						<hr class="hr-print">

						<!-- Print statement -->

						<table class="table table-print">
							<thead>
								<tr>
									<th id="trip_col">Pay Event</th>
									<th id="role_col">Role</th>
									<th>Base Pay</th>
									<th>TL Pay</th>
									<th>Sat Pay</th>
									<th>Bump Pay</th>
									<th>Rig Pay</th>
									<th>Shop Pay</th>
									<th>Other Pay</th>
									<th>Cert. Pay</th>
									<th id="total_col">Total</th>
									<th>Bonus Account</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$locked = $db->select($locked_query, array( "end_date" => $lock_date, "user_id" => $locked_user['user_id_fk']));
								$period_bonus = 0;
								$period_pay = 0;
								$river_days = 0;
								$trip_pay = 0;
								$non_trip_pay = 0;

								////Print out row for each event
								foreach ($locked as $row) {

									$print = false;
									if ($row['trip_id_fk'] && !$row['timesheet_id_fk']) {
										/////Set object ID//////
										$trip->set_trip_id($row['trip_id_fk']);
										//Find out if it is approved
										if ($trip->isApprovedTripBeforeEnd($end_date)) {
											//Set print flag
											$print = true;
											if ($row['guideevent_id_fk']) {
												$trip_days = $trip->riverDays($row['trip_id_fk'], $row['user_id_fk']);
									  		$river_days += $trip_days;
											}
										}
									} else {
										/////Set object ID//////
										$timesheet->set_sheet_id($row['timesheet_id_fk']);
										if ($timesheet->isApprovedTimesheetBeforeEnd($end_date)) {
											//Set print flag
											$print = true;
										}
									}
									if ($print) {
								  	echo '<tr>' . "\n";
								  	echo "<td>";
								  	if ($row['timesheet_id_fk']) {
								  		echo format_date(getEventDate($row['otherevent_id_fk'])); //Timesheet
								  	}
								  	if ($row['trip_id_fk'] && !$row['timesheet_id_fk']) {
								  		echo $trip->getTripStats(); //Trip
								  	}
								  	echo '</td>' . "\n";
								   	echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n"; //Role
								   	echo '<td>'. $row['base_pay'] . '</td>' . "\n"; //Base Pay
								   		$row_total = $row['base_pay'];
								   	echo '<td>'. $row['tl_pay'] . '</td>' . "\n"; //TL Pay
								   		$row_total += $row['tl_pay'];
								   	echo '<td>'. $row['sat_pay'] . '</td>' . "\n"; //Sat Pay
								   		$row_total += $row['sat_pay'];
								   	echo '<td>'. $row['bump_pay'] . '</td>' . "\n"; //Bump Pay
								   		$row_total += $row['bump_pay'];
								   	echo '<td>'. $row['rig_pay'] . '</td>' . "\n"; //Rig Pay
								   		$row_total += $row['rig_pay'];
								   	echo '<td>'. $row['shop_pay'] . '</td>' . "\n"; //Shop Pay
								   		$row_total += $row['shop_pay'];
								   	echo '<td>'. $row['other_pay'] . '</td>' . "\n"; //Other Pay
								   		$row_total += $row['other_pay'];
								   	echo '<td>'. $row['cert_pay'] . '</td>' . "\n"; //Cert. Pay
								   		$row_total += $row['cert_pay'];
								   	echo '<td>'. formatMoney($row_total) . '</td>' . "\n"; //Total
								   		$period_pay += $row_total;
								   	echo '<td>'. $row['bonus_pay'] . '</td>' . "\n"; //Bonus Pay
								   		$period_bonus += $row['bonus_pay'];
								   	echo '</tr>' . "\n";
								   	if ($row['notes'] <> '' || $row['trip_id_fk'] && $row['timesheet_id_fk']) {
								   		echo '<tr id="notes_row">' . "\n";
									   	echo '<td>'; //Associated trip or blank
											  if ($row['trip_id_fk'] && $row['timesheet_id_fk']) {
										  		// change trip object to the trip assoc. with this timesheet event
										  		$trip->set_trip_id($row['trip_id_fk']);
								  				echo '(for: ' . $trip->getTripStats() . ')'; //Trip
										  	} else {
										  		echo "";
										  	}
									   	echo '</td>' . "\n";
									   	echo '<td></td>' . "\n"; //Blank
									   	if ($row['notes']) {
										   	echo '<td><strong>Notes:</strong></td>' . "\n"; //Notes label
									   		echo '<td colspan="9">'. $row['notes'] . '</td>' . "\n";
									   	}
								   		echo '</tr>' . "\n";
								   	}
								  }
								}
								////Print out total row
								echo '<tr>' . "\n";
								echo '<td>River Days: ' . $river_days . '</td>' . "\n"; //Blank
							  echo '<td colspan="9"></td>' . "\n"; //Blank
								echo '<td><strong>'. formatMoney($period_pay) . '</strong></td>' . "\n"; //Total Pay
						   	echo '<td><strong>'. formatMoney($period_bonus) . '</strong></td>' . "\n"; //Bonus Total
						   	echo '</tr>' . "\n";
								?>
							</tbody>
						</table>
					</div> <!--end content column-->
				</div> <!-- .row -->
				<div class="row hidden-print">
					<div class="col-sm-4"></div>
					<div class="col-sm-4">
						<hr class="page-line">
					</div>
				</div>
			<?php
			} //End for each

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
		?>

	</body>
</html>