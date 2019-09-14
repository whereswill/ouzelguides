<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Trips";
	
	$tripevent_id = 0;
	
	if ( !empty($_GET['tripevent_id'])) {
		$tripevent_id = $_REQUEST['tripevent_id'];
		$trip_id = $_REQUEST['trip_id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$tripevent_id = $_POST['tripevent_id'];
		$trip_id = $_REQUEST['trip_id'];
		
		//if trip is approved, un-approve before deleting
		if (!empty($_POST['override_approval'])) {
			$unapproved = unApproveTrip($trip_id, $visitor_id);
		}
		
		// delete data
		$q = $db->delete('other_events','otherevent_id = :tripevent_id', array( "tripevent_id" => $tripevent_id ));
		
		header("Location: /scheduleTrips/schedule-trip.php?trip_id=".$trip_id);
		exit();
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Trip Assignment</h3>
					<?php 
					//INSTANTIATE TRIP///// 
					$trip = new Trip();
					$trip->set_trip_id($trip_id);
					
					if ($trip->isTripLocked()) { ?>
						<form class="form-horizontal" action="/scheduleTrips/delete-tripEvent.php" method="post">
							<p class="alert alert-danger">This trip has already been paid and locked! You cannot delete this trip support.</p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/scheduleTrips/schedule-trip.php?trip_id=<?php echo $trip_id;?>">Back</a>
							</div>
						</form>
					<?php
					} elseif ($trip->isTripApproved()) {
					?>
						<form class="form-horizontal" action="/scheduleTrips/delete-tripEvent.php" method="post">
							<p class="alert alert-warning">This trip is currently approved to pay. Deleting this trip support will un-approve this trip and you will have to review pay and approve again. Do you still want to proceed?</p>
							<div class="form-actions">
								<input type="hidden" name="tripevent_id" value="<?php echo $tripevent_id;?>"/>
								<input type="hidden" name="trip_id" value="<?php echo $trip_id;?>"/>
								<input type="hidden" name="override_approval" value="true"/>
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/scheduleTrips/schedule-trip.php?trip_id=<?php echo $trip_id;?>">No</a>
							</div>
						</form>
					<?php
					} else { 
					?>
						<form class="form-horizontal" action="/scheduleTrips/delete-tripEvent.php" method="post">
							<p class="alert alert-danger">Are you sure you want to delete this Trip Support Assignment?</p>
							<div class="form-actions">
								<input type="hidden" name="tripevent_id" value="<?php echo $tripevent_id;?>"/>
								<input type="hidden" name="trip_id" value="<?php echo $trip_id;?>"/>
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/scheduleTrips/schedule-trip.php?trip_id=<?php echo $trip_id;?>">No</a>
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