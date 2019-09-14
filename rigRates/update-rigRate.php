<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Rig Rates";

	$locked = null;
	$rigrate_id = null;
	
	if ( !empty($_GET['rigrate_id'])) {
		$rigrate_id = $_REQUEST['rigrate_id'];
	}
	
	if ( null==$rigrate_id ) {
		header("Location: /rigRates/rigRates.php");
		exit();
	}
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$rigrate_nameError = null;
		$satellite_boolError = null;
		$turnaround_boolError = null;
		$rig_amountError = null;
		
		// keep track post values
		$rigrate_name = $_POST['rigrate_name'];
		$satellite_bool = $_POST['satellite_bool'];
		$turnaround_bool = $_POST['turnaround_bool'];
		$rig_amount = $_POST['rig_amount'];
		
		// validate input
		$valid = true;
		if (empty($rigrate_name)) {
			$rigrate_nameError = 'Please enter a name for this Rig Rate';
			$valid = false;
		}

		if (empty($satellite_bool)) {
			$satellite_boolError = 'Please enter whether Satellite or Local';
			$valid = false;
		}

		if (empty($turnaround_bool)) {
			$turnaround_boolError = 'Please enter whether the rig is a Fresh pack or a Turnaround';
			$valid = false;
		}

		if (empty($rig_amount)) {
			$rig_amountError = 'Please enter the dollar amount for this Rig Rate';
			$valid = false;
		}
	
		// update data
		if ($valid) {
			$rigrate_array = array(
		    'rigrate_name' => $rigrate_name,
		    'satellite_bool' => $satellite_bool,
		    'turnaround_bool' => $turnaround_bool,
		    'rig_amount' => $rig_amount,
			);
			$success = $db->update("rig_rates", $rigrate_array, "rigrate_id = :rigrate_id", array("rigrate_id" => $rigrate_id));
	
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = unApproveAllTrips($visitor_id);
				}
			}
	
			header("Location: /rigRates/update-rigRate.php?rigrate_id=$rigrate_id&update=$success");
			exit();
		}
	} else {
		$data = $db->select("SELECT * FROM `rig_rates` WHERE `rigrate_id` = :rigrate_id", array( "rigrate_id" => $rigrate_id ));
		$data = $data[0];
		$rigrate_name = $data['rigrate_name'];
		$satellite_bool = $data['satellite_bool'];
		$turnaround_bool = $data['turnaround_bool'];
		$rig_amount = $data['rig_amount'];
	}
	
	$locked = "true";

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

			if (empty($_GET['update']) && $locked) { ?>
			<div id="Alert" class="alert alert-warning">
			   <strong>Warning!</strong> This is a system Rate. Some fields are not editable.
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
					<form class="form-horizontal" action="/rigRates/update-rigRate.php?rigrate_id=<?php echo $rigrate_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Rig Rate</legend>
							<?php
								include 'rigRate_formGroup.php';
							?>							
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<?php if (!areTripsApproved()) { ?>
										<button type="submit" class="btn btn-low btn-info">Update</button>
									<?php } else { ?>
										<input type="hidden" name="override_approval" value="true">
										<button  type="submit" class="btn btn-low btn-info" data-toggle="modal" data-target="#approvalModal">Update</button>
									<?php } ?>	
									
										<!-- Update Modal for role that is on an approved trip-->
										<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										  <div class="modal-dialog">
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
										        <h4 class="modal-title" id="myModalLabel">There are approved trips that may be using this Rate!</h4>
										      </div>
										      <div class="modal-body">
										        <p>This rate may exist on trips that are currently approved to pay. Updating this rate will un-approve ALL trips. You will need to review the pay and approve them again. Are you sure?</p>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-low btn-default" data-dismiss="modal">Close</button>
										        <button type="submit" class="btn btn-low btn-info">Update</button>
										      </div>
										    </div>
										  </div>
										</div>  <!--end modal-->
									<a class="btn btn-low btn-primary" href="/rigRates/rigRates.php">Back</a>
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