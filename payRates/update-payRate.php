<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Pay Rates";
	
	//$locked = null;
	$payrate_id = null;
	
	if ( !empty($_GET['payrate_id'])) {
		$payrate_id = $_REQUEST['payrate_id'];
	}
	
	if ( null==$payrate_id ) {
		header("Location: /payRates/payRates.php");
		exit();
	}
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$payrate_nameError = null;
		$rateError = null;
		$descriptionError = null;
		$active_Error = null;
		
		// keep track post values
		$payrate_name = $_POST['payrate_name'];
		$rate = $_POST['rate'];
		$description = $_POST['description'];
		$active = $_POST['active'];
		
		// validate input
		$valid = true;
		if (empty($payrate_name)) {
			$payrate_nameError = 'Please enter a name for this trip type';
			$valid = false;
		}

		if (empty($rate)) {
			$rateError = 'Please enter the dollar amount for this rate level';
			$valid = false;
		}

		if (empty($active)) {
			$activeError = 'Please check whether role is Active';
			$valid = false;
		}
		
		// update data
		if ($valid) {
			$payrate_array = array(
		    'payrate_name' => $payrate_name,
		    'rate' => $rate,
		    'description' => $description,
		    'active' => $active,
			);
			$success = $db->update("pay_rates", $payrate_array, "payrate_id = :payrate_id", array("payrate_id" => $payrate_id));
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = unApproveAllTrips($visitor_id);
				}
			}
			
			header("Location: /payRates/update-payRate.php?payrate_id=$payrate_id&update=$success");
			exit();
			
		}
	} else {
		$data = $db->select("SELECT * FROM `pay_rates` WHERE `payrate_id` = :payrate_id", array( "payrate_id" => $payrate_id ));
		$data = $data[0];
		$payrate_name = $data['payrate_name'];
		$rate = $data['rate'];
		$description = $data['description'];
		$active = $data['active'];

		// if (isRateAssigned($payrate_id)) {
		// 	$locked = "true";
		// }
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

			if (empty($_GET['update'])) { // && $locked ?>
				<div id="Alert" class="alert alert-warning">
				   <strong>Warning!</strong> This Pay Rate has been assigned to a guide. Some fields are no longer editable.
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
					<form class="form-horizontal" action="/payRates/update-payRate.php?payrate_id=<?php echo $payrate_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Pay Rate</legend>
							<?php
								include 'payRate_formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">

									<?php if (!areTripsApproved()) { ?>
										<button type="submit" class="btn btn-low btn-info">Update</button>
									<?php } else { ?>
										<input type="hidden" name="override_approval" value="true">
										<button  type="submit" class="btn btn-low btn-info" data-toggle="modal" data-target="#approvalModal">Update</button>
									<?php } ?>	
								
									<!-- Update Modal for rate if there is an approved trip-->
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
									
									<a class="btn btn-low btn-primary" href="/payRates/payRates.php">Back</a>
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