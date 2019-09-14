<?php 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Trips";

	$trip_id = null;
	if ( !empty($_GET['trip_id'])) {
		$trip_id = $_REQUEST['trip_id'];
	}
	
//IF TRIP IS LOCKED, REDIRECT TO PAY TRIPS/////////////////

	$trip = new Trip();
	$trip->set_trip_id($trip_id);
	
	if ($trip->isTripLocked()) {
		header("Location: /payTrips/pay-trip.php?trip_id=".$trip_id);
		exit();
	}
	
	if ( null==$trip_id ) {
		header("Location: /trips/trips.php");
		exit();
	}
	
	if ( !empty($_POST)) {
		//print_r($_REQUEST);
		// keep track validation errors
		$river_trips_fkError = null;
		$trip_types_fkError = null;
		$putin_dateError = null;
		$takeout_dateError = null;
		$guests_numError = null;
		$turnaroundError = null;
		
		// keep track post values
		$river_trips_fk = $_POST['river_trips_fk'];
		$trip_types_fk = $_POST['trip_types_fk'];
		$putin_date = $_POST['putin_date'];
		$takeout_date = $_POST['takeout_date'];
		$guests_num = $_POST['guests_num'];
		$turnaround = $_POST['turnaround'];
		
		// validate input
		$valid = true;
		if (empty($river_trips_fk)) {
			$river_trips_fkError = 'Please enter a trip name';
			$valid = false;
		}
		
		if (empty($trip_types_fk)) {
			$trip_types_fkError = 'Please enter a trip type';
			$valid = false;
		//} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			//$emailError = 'Please enter a valid Email Address';
			//$valid = false;
		}
		
		if (empty($putin_date)) {
			$putin_dateError = 'Please enter a put-in date';
			$valid = false;
		}

		if (empty($takeout_date)) {
			$takeout_dateError = 'Please enter a take-out date';
			$valid = false;
		} else if (new DateTime($putin_date) > new DateTime($takeout_date)) {
			$takeout_dateError = 'Please choose a date that is later than the put-in date';
			$valid = false;
		}
		
		if (empty($guests_num)) {
			$guests_numError = 'Please enter the number of guests';
			$valid = false;
		}

		if (empty($turnaround)) {
			$turnaroundError = 'Please select whether the trip is a Turnaround or Fresh Pack';
			$valid = false;
		}

		// update data
		if ($valid) {
			$trip_array = array(
		    "river_trips_fk" => "$river_trips_fk",
		    "trip_types_fk" => "$trip_types_fk",
		    "putin_date" => "$putin_date",
		    "takeout_date" => "$takeout_date",
		    "guests_num" => "$guests_num",
		    "turnaround" => "$turnaround",
		    "updated_on" => "$datetime",
		    "updated_by" => "$visitor_id",
			);
			$success = $db->update("trips", $trip_array, "trip_id = :trip_id",array( "trip_id" => $trip_id));
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$unapproved = unApproveTrip($trip_id, $visitor_id);
					//echo $unapproved;
					header("Location: /payTrips/pay-trip.php?trip_id=$trip_id");
					exit();
				}
			} else {
				header("Location: /trips/trips.php");
				exit();
			}
			
		}
	} else {
		//INSTANTIATE TRIP///// 
		$trip = new Trip();
		$trip->set_trip_id($trip_id);
		
		$data = $trip->getTripDetails();
		
		$river_trips_fk = $data['river_trips_fk'];
		$trip_types_fk = $data['trip_types_fk'];
		$putin_date = $data['putin_date'];
		$takeout_date = $data['takeout_date'];
		$guests_num = $data['guests_num'];
		$approved_on = $data['approved_on'];

		if ($data['turnaround']  == "Y") {
			$turnaround = 'Y';
		} elseif ($data['turnaround']  == NULL) {
			$turnaround = NULL;
		} else {
			$turnaround = 'N';
		}

	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>
			
			<div class="row">
				<div class="col-sm-7"> <!--start content column-->	
					<form class="form-horizontal" action="/trips/update-trip.php?trip_id=<?php echo $trip_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Trip</legend>
							<?php
								include 'trip-formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<?php 
									//INSTANTIATE TRIP///// 
									$trip = new Trip();
									$trip->set_trip_id($trip_id);
									
									if (!$trip->isTripLocked()) {	
										if (empty($approved_on)) { ?>
											<button type="submit" class="btn btn-low btn-info">Update</button>
										<?php } else { ?>
											<input type="hidden" name="override_approval" value="true">
											<button  type="submit" class="btn btn-low btn-info" data-toggle="modal" data-target="#approvalModal">Update</button>
										<?php }
									} ?>
									
									<!-- Update Modal for approved trip-->
									<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
									    <div class="modal-content">
									      <div class="modal-header">
									        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
									        <h4 class="modal-title" id="myModalLabel">This trip has been approved to pay!</h4>
									      </div>
									      <div class="modal-body">
									        <p>This trip has previously been approved to pay. Updating this trip will un-approve this trip. You will need to review the pay and approve it again.</p>
									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-low btn-default" data-dismiss="modal">Close</button>
									        <button type="submit" class="btn btn-low btn-info">Update</button>
									      </div>
									    </div>
									  </div>
									</div>
									
									<!--<a class="btn btn-primary" href="<?php //echo $previous?>">Back</a>-->
									<a class="btn btn-low btn-primary" href="/trips/trips.php">Back To Trips</a>
								</div>
							</div>
						</fieldset>
					</form>
				</div> <!--end content column-->

				<!--start Notes right side column-->	
				<div class="col-sm-5"> 
					<div class="notes">
						<legend>Trip Notes</legend>
				    <div class="notes-notes">

				    </div>
					</div>
				</div>

			</div><!--.row-->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>