<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Trip Types";
	
	$locked = null;

	$triptype_id = null;
	if ( !empty($_GET['triptype_id'])) {
		$triptype_id = $_REQUEST['triptype_id'];
	}
	
	if ( null==$triptype_id ) {
		header("Location: /tripTypes/tripTypes.php");
		exit();
	}
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$triptype_nameError = null;
		$descriptionError = null;
		$dd_orderError = null;
		$activeError = null;
		
		// keep track post values
		$triptype_name = $_POST['triptype_name'];
		$description = $_POST['description'];
		$dd_order = $_POST['dd_order'];
		$active = $_POST['active'];
		
		// validate input
		$valid = true;
		if (empty($triptype_name)) {
			$triptype_nameError = 'Please enter a name for this trip type';
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
			$triptype_array = array(
		    'triptype_name' => $triptype_name,
		    'description' => $description,
		    'dd_order' => $dd_order,
		    'active' => $active,
			);
			$success = $db->update("trip_types", $triptype_array, "triptype_id = :triptype_id", array("triptype_id" => $triptype_id));
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = unApproveTripType($triptype_id, $visitor_id);
				}
			}

			header("Location: /tripTypes/update-tripType.php?triptype_id=$triptype_id&update=$success");
			exit();
		}
	} else {
		$data = $db->select("SELECT * FROM `trip_types` WHERE `triptype_id` = :triptype_id", array( "triptype_id" => $triptype_id ));
		$data = $data[0];
		$triptype_name = $data['triptype_name'];
		$description = $data['description'];
		$dd_order = $data['dd_order'];
		$active = $data['active'];
		
		if (tripTypeLocked($triptype_id) || !isTypeEditable($triptype_id)) {
			$locked = "true";
		}
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

			if (!isTypeEditable($triptype_id)){ //add if locked	?>
				<div id="Alert" class="alert alert-warning">
				   <strong>Warning!</strong> This is a system Type. Some fields are no longer editable.
				</div>
			<?php } else if (empty($_GET['update']) && $locked) { ?>
				<div id="Alert" class="alert alert-warning">
				   <strong>Warning!</strong> There is locked trips with this trip type. Some fields are no longer editable
				</div>

			<?php }

			if ( !empty($_GET['update'])) {
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
					<form class="form-horizontal" action="/tripTypes/update-tripType.php?triptype_id=<?php echo $triptype_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Trip Type</legend>
							<?php
								include 'tripType_formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
						
									<?php if (!isTypeApproved($triptype_id)) { ?>
										<button type="submit" class="btn btn-low btn-info">Update</button>
									<?php } else { ?>
										<input type="hidden" name="override_approval" value="true">
										<button  type="submit" class="btn btn-low btn-info" data-toggle="modal" data-target="#approvalModal">Update</button>
									<?php } ?>	
										<!-- Update Modal for guide who is on an approved trip-->
										<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										  <div class="modal-dialog">
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
										        <h4 class="modal-title" id="myModalLabel">This trip type is on approved trips!</h4>
										      </div>
										      <div class="modal-body">
										        <p>This trip type has been assigned to trips that are currently approved to pay. Updating this trip type will un-approve these trips. You will need to review the pay and approve them again.</p>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-low btn-default" data-dismiss="modal">Close</button>
										        <button type="submit" class="btn btn-low btn-info">Update</button>
										      </div>
										    </div>
										  </div>
										</div>
						
									<a class="btn btn-low btn-primary" href="/tripTypes/tripTypes.php">Back</a>
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