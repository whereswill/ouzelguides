<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "River Trips";
	
	$locked = null;
	$user_id_fk = null;

	$rivertrip_id = null;
	if ( !empty($_GET['rivertrip_id'])) {
		$rivertrip_id = $_REQUEST['rivertrip_id'];
	}
	
	if ( null==$rivertrip_id ) {
		header("Location: /riverTrips/riverTrips.php");
		exit();
	}
	
	//print_r($_POST);
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$rivertrip_nameError = null;
		$longnameError = null;
		$drainageError = null;
		$putin_nameError = null;
		$takeout_nameError = null;
		$satelliteError = null;
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
		
		// update data
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
			$update_success = $db->update("river_trips", $rivertrip_array, "rivertrip_id = :rivertrip_id", array("rivertrip_id" => $rivertrip_id));
			if ($update_success == TRUE) {
				$success = TRUE;
			}
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $update_success == TRUE) {
					$success = unApproveTripName($rivertrip_id, $visitor_id);
				}
			}

			header("Location: /riverTrips/update-riverTrip.php?rivertrip_id=$rivertrip_id&update=$success");
			exit();
		}
	} else {
		$data = $db->select("SELECT * FROM `river_trips` WHERE `rivertrip_id` = :rivertrip_id", array( "rivertrip_id" => $rivertrip_id ));
		$data = $data[0];
		//print_r($data);
		$rivertrip_name = $data['rivertrip_name'];
		$longname = $data['longname'];
		$drainage = $data['drainage'];
		$putin_name = $data['putin_name'];
		$takeout_name = $data['takeout_name'];
		$satellite = $data['satellite'];
		$mileage = $data['mileage'];
		$description = $data['description'];
		$dd_order = $data['dd_order'];
		$active = $data['active'];
		
		if (riverNameLocked($rivertrip_id)) {
			$locked = "true";
		}
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
			if (empty($_GET['update']) && $locked) { ?>
				<div id="Alert" class="alert alert-warning">
				   <strong>Warning!</strong> There is locked trips with this trip name. Some fields are no longer editable
				</div>

			<?php }
			
			if ( isset($_GET['update'])) {
				if ($_GET['update'] == 1) { ?>
					<div id="Alert" class="alert alert-success">
					   <strong>Success!</strong> The update was successful.
					</div>
				<?php } else { ?>
					<div id="Alert" class="alert alert-warning">
					   <strong>Warning!</strong> There was a problem with your update.
					</div>
				<?php } 
			} ?>

			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->	
					<form class="form-horizontal" action="/riverTrips/update-riverTrip.php?rivertrip_id=<?php echo $rivertrip_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Trip Name</legend>
							<?php
								include 'riverTrip_formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									
									<?php if (!isNameApproved($rivertrip_id)) { ?>
										<button type="submit" class="btn btn-info">Update</button>
									<?php } else { ?>
										<input type="hidden" name="override_approval" value="true">
										<button  type="submit" class="btn btn-info" data-toggle="modal" data-target="#approvalModal">Update</button>
									<?php } ?>	
										<!-- Update Modal for guide who is on an approved trip-->
										<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										  <div class="modal-dialog">
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
										        <h4 class="modal-title" id="myModalLabel">This Trip Name is on approved trips!</h4>
										      </div>
										      <div class="modal-body">
										        <p>This name has been assigned to trips that are currently approved to pay. Updating this name will un-approve these trips. You will need to review the pay and approve them again.</p>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										        <button type="submit" class="btn btn-info">Update</button>
										      </div>
										    </div>
										  </div>
										</div>
									
									<a class="btn btn-primary" href="/riverTrips/riverTrips.php">Back</a>
								</div>
							</div>
						</fieldset>
					</form>
				</div> <!--end content column-->
			</div><!--.row-->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>