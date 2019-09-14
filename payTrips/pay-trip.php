<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title////////
	$title = "Review Pay";

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
	
	if ( null==$trip_id) {
		header("Location: /trips/trips.php");
		exit();
	} else {
		//INSTANTIATE TRIP///// 
		$trip = new Trip();
		$trip->set_trip_id($trip_id);
		
		if ($trip->isTripApproved() || $trip->isTripLocked()) {
			$queryStr = $_SERVER['QUERY_STRING'];
			header("Location: /payTrips/view-approved.php?$queryStr");
    	exit();
		}
		
		$data = $trip->getTripDetails();
		
		$name = $db->select("SELECT * FROM `river_trips` WHERE `rivertrip_id` = :river_trips_fk", array( "river_trips_fk" => $data['river_trips_fk'] ));
		$name = $name[0];
		
		$type = $db->select("SELECT * FROM `trip_types` WHERE `triptype_id` = :trip_types_fk", array( "trip_types_fk" => $data['trip_types_fk'] ));
		$type = $type[0];
		
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

////ALERT AREA
	
	if ( !empty($_GET['alert'])) {
		if ($_GET['alert'] == 1) { ?>
			<div id="Alert" class="alert alert-success">
			   <strong>Success!</strong> The approval was successful.
			</div>
		<?php } else if ($_GET['alert'] == 2) { ?>	
			<div id="Alert" class="alert alert-success">
			   <strong>Success!</strong> This trip has been successfully Unapproved.
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
								<legend>Trip Details</legend>
							</div>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-primary pull-left btn-style-left" href="/trips/trips.php">Back To Trips</a>
							<?php if (!$trip->isTripLocked()) { ?>
							<a class="btn btn-info pull-left btn-style-left" href="/scheduleTrips/schedule-trip.php?trip_id=<?php echo $trip_id;?>">Edit Schedule</a>
							<?php }
							if ($trip->numberOfAssigned() > 0 && !$trip->isTripLocked()) { ?>
							<a class="btn btn-success pull-left btn-style-left" id="btn-approve" href="javascript:void(0);">Approve Pay</a>
							<?php }
							if ($trip->isTripApproved()) { ?>
							<a href="/trips/create-trip.php" class="btn btn-warning pull-left btn-style-left">Create Trip</a>
							<?php }
							if ($target) { 
							echo '<a href="' . ASSession::get("target") . '" class="btn btn-warning pull-left btn-style-left">' . ASSession::get("return_btn") . '</a>';
							}  ?>
						</div> <!--end split column-->
						
						<?php include $_SERVER['DOCUMENT_ROOT'].'/scheduleTrips/trip-detailsGroup.php'; ?>
						
					</div><!--form-horizontal-->
				</div> <!--end content column-->
			</div><!--.row-->
			
<!--ALL SCHEDULED TABLE AREA-->

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-12">
						<legend>Trip Work</legend>
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
							$guide_events = $db->select("SELECT * FROM guide_events WHERE `trip_id_fk` = :trip_id ORDER BY guideevent_id ASC", array( "trip_id" => $trip_id ));
							if (count($guide_events) > 0) {
							
								///////INSTANTIATE GUIDE AND PAY OBJECT///////////
								$pay = new PayTrip($trip, $guide_events[0]['user_id_fk']);
								$guide = new Guide();
								foreach ($guide_events as $row) {
									$guide_total = 0;
									$pay->set_guide_id($row['user_id_fk']);
									$guide->set_guide_id($row['user_id_fk']);
								
									echo '<tr>' . "\n";
								
									echo '<td>'. $guide->getUserName() . '</td>' . "\n";
								
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
								
									if(!isSwamper($row['role_id_fk'])){
										$base_pay = $pay->getBasePay();
										$guide_total = $base_pay;
										echo '<td>'. formatMoney($base_pay) . '</td>' . "\n"; //Base Pay
									} else {
										echo '<td>' . formatMoney(0) . '</td>' . "\n"; //Base Pay
									}
								
									if(isTL($row['role_id_fk'])){
										$tl_pay = $pay->getTLPay();
										$guide_total = $guide_total + $tl_pay;
										echo '<td>'. formatMoney($tl_pay) . '</td>' . "\n"; //TL Pay
									} else {
										echo '<td>' . formatMoney(0) . '</td>' . "\n"; //TL Pay
									}

									if($trip->isSat()){
										$sat_pay = $pay->getSatPay();
										$guide_total = $guide_total + $sat_pay;
										echo '<td>'. formatMoney($sat_pay) . '</td>' . "\n"; //Sat Pay
									} else {
										echo '<td>' . formatMoney(0) . '</td>' . "\n"; //Sat Pay
									}
								
									if(!isSwamper($row['role_id_fk'])){
										$bumps = $pay->getBumpPay();
										$bump_pay = $bumps['pay'];
										$guide_total = $guide_total + $bump_pay;
										if ($bumps['ump_bump']) {
											?>
											<script type="text/javascript">
		    								var ump_pay = <?php echo json_encode(true) ?>;
			    							var trip_id = <?php echo json_encode($trip_id) ?>;
											</script>
											<?php
										} else {																		
											?>
											<script type="text/javascript">
			    							var trip_id = <?php echo json_encode($trip_id) ?>;
											</script>
											<?php
										}
										echo '<td>'. formatMoney($bump_pay) . '</td>' . "\n"; //Bump Pay
									} else {
										echo '<td>' . formatMoney(0) . '</td>' . "\n"; //Bump Pay
									}
								
									if(!$trip->isDay() && $row['rigger_bool'] == 'Y'){
										$rig_pay = $pay->getRigPay();
										$guide_total = $guide_total + $rig_pay;
										echo '<td>'. formatMoney($rig_pay) . '</td>' . "\n"; //Rig Pay
									} else {
										echo '<td>' . formatMoney(0) . '</td>' . "\n"; //Rig Pay
									}
									
									if($row['food_shopper_bool'] == 'Y'){
										$shop_pay = $pay->getShopPay();
										$guide_total = $guide_total + $shop_pay;
										echo '<td>'. formatMoney($shop_pay) . '</td>' . "\n"; //Shop Pay
									} else {
										echo '<td>' . formatMoney(0) . '</td>' . "\n"; //Shop Pay
									}									
							
									$adjust_pay = $row['adjust_amount'];
									$guide_total = $guide_total + $adjust_pay;
									echo '<td>'. formatMoney($adjust_pay) . ' ' . "\n"; //Other Pay
									if (!empty($row['adjust_notes'])) {
										echo '<div class="note_popup" title="Notes" data-placement="left" data-content="' . $row['adjust_notes'] . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div></td>' . "\n"; //Info Icon
									}
								
									$cert_pay = $pay->getCertPay();
									$guide_total = $guide_total + $cert_pay;
									echo '<td>'. formatMoney($cert_pay) . '</td>' . "\n"; //Cert Pay
								
									echo '<td>'. formatMoney($guide_total) . '</td>' . "\n"; //Total
									echo '<td>'. formatMoney($pay->getBonusPay()) . '</td>' . "\n"; //Bonus Pay
									echo '</tr>' . "\n";
								
									$trip_total = $trip_total + $guide_total;
								}
							}
							
							//OTHERS TABLE AREA

							$other_events = $db->select("SELECT * FROM other_events WHERE `trip_id_fk` = :trip_id AND `timesheet_id_fk` IS NULL ORDER BY otherevent_id ASC", array( "trip_id" => $trip_id ));
							if (count($other_events) > 0) {
							
								///////INSTANTIATE USER OBJECT///////////
								$user = new User();
							
								foreach ($other_events as $row) {
								
									$user->set_user_id($row['user_id_fk']);
								
									echo '<tr>' . "\n";
									echo '<td>'. $user->getUserName() . '</td>' . "\n";
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
									echo '<td></td>' . "\n"; //Base Pay
									echo '<td></td>' . "\n"; //TL Pay
									echo '<td></td>' . "\n"; //Sat Pay
									echo '<td></td>' . "\n"; //Bump Pay
									echo '<td></td>' . "\n"; //Rig Pay
									echo '<td></td>' . "\n"; //Shop Pay
								
									$other_pay = $row['event_amount'];
									$other_total = $other_pay;
									echo '<td>'. formatMoney($other_pay) . ' '; //Other Pay
									if (!empty($row['event_notes'])) {
										echo '<div class="note_popup" title="Notes" data-placement="left" data-content="' . $row['event_notes'] . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div></td>' . "\n"; //Info Icon
									}
								
									echo '<td></td>' . "\n"; //Cert Pay
								
									echo '<td>'. formatMoney($other_total) . '</td>' . "\n"; //Total
								
									echo '<td></td>' . "\n"; //Bonus Pay
									echo '</tr>' . "\n";
								
									$trip_total = $trip_total + $other_total;
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

			<!--TIMESHEET SCHEDULED TABLE AREA-->
			
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
						<tbody>
							<?php
								foreach ($other_events as $row) {
								
									$user->set_user_id($row['user_id_fk']);
								
									echo '<tr>' . "\n";
									echo '<td>'. $user->getUserName() . '</td>' . "\n";
									echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";

									$other_pay = $row['event_amount'];
									$other_total = $other_pay;
									echo '<td>'. formatMoney($other_pay) . ' '; //Other Pay
									if (!empty($row['event_notes'])) {
										echo '<div class="note_popup" title="Notes" data-placement="left" data-content="' . $row['event_notes'] . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div></td>' . "\n"; //Info Icon
									}
								
									echo '<td>'. formatMoney($other_total) . '</td>' . "\n"; //Total
								
									echo '</tr>' . "\n";
								
									$trip_total = $trip_total + $other_total;
								}

							
							echo '<tr class="total-row">' . "\n";
							echo '<td></td>' . "\n";
							echo '<td></td>' . "\n";
							echo '<td>Trip Total</td>' . "\n"; //Cert Pay
							echo '<td>'. formatMoney($trip_total) . '</td>' . "\n"; //Total
							echo '</tr>' . "\n";
							?>
						</tbody>
					</table>
				</div>
			</div><!--row-->
			<?php
			}
		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
		?>

		<!-- Ump Bump modal -->
		<div class="modal fade" id="umpModal" tabindex="-1" role="dialog">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">Umpqua Bumps</h4>
		      </div>
		      <div class="modal-body">
		        <p>This approval includes Umpqua bump(s). If this trip is part of an Umpqua series, please do not approve this trip until the entire series is entered.</p>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Don't Approve</button>
		        <a class="btn btn-large btn-success" href="/payTrips/approve-trip-pay.php?trip_id=<?php echo $trip_id;?>">Approve Pay</a>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->


		<script type="text/javascript">
    $(document).ready(function() {

      //show modal if there are Umpqua bumps
      $('#btn-approve').click(function(event) {
         if (typeof ump_pay !== 'undefined') {
         	$('#umpModal').modal('show');
         } else {
         	window.location.href='/payTrips/approve-trip-pay.php?trip_id='+trip_id;
         };
      });

    } );
    </script>

	</body>
</html>