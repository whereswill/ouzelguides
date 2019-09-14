<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "River Trips";

	$rivertrip_id = 0;
	
	if ( !empty($_GET['rivertrip_id'])) {
		$rivertrip_id = $_REQUEST['rivertrip_id'];
		
		//check for rivertrip_id in trips table
		$result = $db->select("SELECT COUNT(trip_id) AS num FROM `trips` WHERE `river_trips_fk` = :r", array( "r" => $rivertrip_id));
        $tripsWithThisName = $result[0]['num'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$rivertrip_id = $_POST['rivertrip_id'];

		// delete data
		$q = $db->delete('river_trips','rivertrip_id = :rivertrip_id', array( "rivertrip_id" => $rivertrip_id ));
		header("Location: /riverTrips/riverTrips.php");
		exit();

	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Trip Name</h3>
					<?php
					if (riverNameLocked($rivertrip_id)) { 
					?>
						<form class="form-horizontal" action="/riverTrips/delete-riverTrip.php" method="post">
							<p class="alert alert-danger">You cannot delete this Trip Name. There are currently locked trip(s) with this Trip Name. Please deactivate this Trip Name if you no longer wish to use it. </p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/riverTrips/riverTrips.php">Back</a>
							</div>
						</form>
					<?php
					} else if ($tripsWithThisName > 0) { 
					?>
						<form class="form-horizontal" action="/riverTrips/delete-riverTrip.php" method="post">
							<p class="alert alert-danger">You cannot delete this Trip Name. There are currently <?php echo $tripsWithThisName;?> scheduled trip(s) with this Trip Name. Please edit the Trips to change the river trip before deleting this Trip Name. </p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/riverTrips/riverTrips.php">Back</a>
							</div>
						</form>
					<?php
					} else {
					?>
						<form class="form-horizontal" action="/riverTrips/delete-riverTrip.php" method="post">
							<p class="alert alert-warning">Are you sure you want to delete this Trip Name?</p>
							<div class="form-actions">
								<input type="hidden" name="rivertrip_id" value="<?php echo $rivertrip_id;?>"/>
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/riverTrips/riverTrips.php">No</a>
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