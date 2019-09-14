<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Trips";

	$trip_id = 0;
	
	if (!empty($_GET['trip_id']) || !empty($_POST)) {
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
	
	if ( !empty($_POST)) {
		
		//if trip is approved, un-approve before deleting
		if (!empty($_POST['override_approval'])) {
			$unapproved = unApproveTrip($trip_id, $visitor_id);
		}

		//if trip has a timesheet event associated, remove trip ID and put in notes
		if ($trip->isTripOnSheet()) {
			$remove = $trip->removeTripFromSheet();
			foreach ($remove as $key) {
				$unapproved = unApproveTimesheet($key['timesheet_id_fk'], $visitor_id);
			}
		}

		// delete guides
		$q = $db->deleteAll('guide_events','trip_id_fk = :trip_id', array( "trip_id" => $trip_id ));
		// delete others
		$q = $db->deleteAll('other_events','trip_id_fk = :trip_id', array( "trip_id" => $trip_id ));
		// delete trip
		$q = $db->delete('trips','trip_id = :trip_id', array( "trip_id" => $trip_id ));

		header("Location: /trips/trips.php");	
		exit();
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Trip</h3>
					<?php
					if ($trip->isTripLocked()) { 
					?>
						<form class="form-horizontal" action="/trips/delete-trip.php" method="post">
							<p class="alert alert-danger">This trip has already been paid and locked! You cannot delete this trip.</p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/trips/trips.php">Back</a>
							</div>
						</form>
					<?php
					} elseif ($trip->isTripApproved()) { 
					?>
						<form class="form-horizontal" action="/trips/delete-trip.php" method="post">
							<input type="hidden" name="trip_id" value="<?php echo $trip_id;?>"/>
							<input type="hidden" name="override_approval" value="true"/>
							<p class="alert alert-warning">This trip is currently approved to pay. Deleting this trip will remove anyone assigned to this trip and remove the trip from the approved queue. Do you still want to proceed?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/trips/trips.php">No</a>
							</div>
						</form>
					<?php
					} elseif ($trip->numberOfAssigned() > 0){ 
					?>
						<form class="form-horizontal" action="/trips/delete-trip.php" method="post">
							<input type="hidden" name="trip_id" value="<?php echo $trip_id;?>"/>
							<p class="alert alert-warning">There are currently <?php echo $trip->numberOfAssigned();?> people scheduled on this trip. Deleting this trip will also remove anyone assigned to this trip. Do you still want to proceed?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/trips/trips.php">No</a>
							</div>
						</form>
					<?php
					} else {
					?>
						<form class="form-horizontal" action="/trips/delete-trip.php" method="post">
							<input type="hidden" name="trip_id" value="<?php echo $trip_id;?>"/>
							<p class="alert alert-danger">Are you sure you want to delete this trip?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/trips/trips.php">No</a>
							</div>
						</form>	
					<?php 
					}
					?>
				</div> <!--end content column-->
			</div> <!--.row-->
<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; 
?>

	</body>
</html>