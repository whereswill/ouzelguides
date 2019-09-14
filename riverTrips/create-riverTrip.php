<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "River Trips";

	if ( !empty($_POST)) {
		// keep track validation errors
		$rivertrip_nameError = null;
		$longnameError = null;
		$drainageError = null;
		$putin_nameError = null;
		$takeout_nameError = null;
		$satellite = null;
		$mileageError = null;
		$descriptionError = null;
		$dd_orderError = null;
		$activeError = null;
		
		// keep track post values
		$rivertrip_name = $_POST['rivertrip_name'];
		$longname = $_POST['longname'];
		$drainage = $_POST['drainage'];
		$putin_name = $_POST['putin_name'];
		$takeout_name = $_POST['takeout_name'];
		$satellite = $_POST['satellite'];
		$mileage = $_POST['mileage'];
		$description = $_POST['description'];
		$dd_order = $_POST['dd_order'];
		$active = $_POST['active'];
		
		// validate input
		$valid = true;
		if (empty($rivertrip_name)) {
			$rivertrip_nameError = 'Please enter a river trip short name';
			$valid = false;
		}
		
		if (empty($longname)) {
			$longnameError = 'Please enter a long name';
			$valid = false;
		}
		
		if (empty($drainage)) {
			$drainageError = 'Please enter a drainage for this river trip';
			$valid = false;
		}

		if (empty($putin_name)) {
			$putin_nameError = 'Please enter the name of the typical put-in';
			$valid = false;
		}

		if (empty($takeout_name)) {
			$takeout_nameError = 'Please enter the name of the typical take-out';
			$valid = false;
		}

		if (empty($satellite)) {
			$satelliteError = 'Please select whether the trip is Local or Satellite';
			$valid = false;
		}

		if (empty($mileage)) {
			$mileageError = 'Please enter the number of miles guides will log on this trip';
			$valid = false;
		}

		if (empty($dd_order)) {
			$dd_orderError = 'Please enter the order the trip should appear in drop-down selections';
			$valid = false;
		}
		
		if (empty($active)) {
			$activeError = 'Please check whether trip name is Active';
			$valid = false;
		}
		
		// insert data
		if ($valid) {		
			$rivertrip_array = array(
			    'rivertrip_name' => $rivertrip_name,
			    'longname' => $longname,
			    'drainage' => $drainage,
			    'putin_name' => $putin_name,
			    'takeout_name' => $takeout_name,
			    'satellite' => $satellite,
			    'mileage' => $mileage,
			    'description' => $description,
			    'dd_order' => $dd_order,
			    'active' => $active,
				);
			$q = $db->insert('river_trips',$rivertrip_array);
			header("Location: /riverTrips/riverTrips.php");
			exit();
		}
	}
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->	
					<form class="form-horizontal" action="/riverTrips/create-riverTrip.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a Trip Name</legend>
							<?php
								include 'riverTrip_formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<button type="submit" class="btn btn-warning">Create</button>
									<a class="btn btn-primary" href="<?php echo $previous?>">Back</a>
								</div>
							</div>
						</fieldset>
					</form>
				</div> <!--end content column-->
			</div><!--row-->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>