<?php 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Certifications";

	$locked = null;
	$certrate_id = null;
	
	if ( !empty($_GET['certrate_id'])) {
		$certrate_id = $_REQUEST['certrate_id'];
	}
	
	if ( null==$certrate_id ) {
		header("Location: /certRates/certRates.php");
		exit();
	}
	
	//print_r($_POST);
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$certrate_nameError = null;
		$cert_typeError = null;
		$cert_amountError = null;
		$descriptionError = null;
		
		// keep track post values
		$certrate_name = $_POST['certrate_name'];
		$cert_type = $_POST['cert_type'];
		$cert_amount = $_POST['cert_amount'];
		$description = $_POST['description'];
		
		// validate input
		$valid = true;
		if (empty($certrate_name)) {
			$certrate_nameError = 'Please enter a name for this certification type';
			$valid = false;
		}

		if (empty($description)) {
			$descriptionError = 'Please enter a description of this certification level';
			$valid = false;
		}

		if (empty($cert_amount)) {
			$cert_amountError = 'Please enter the dollar amount for this certification level';
			$valid = false;
		}
	
		// update data
		if ($valid) {
			$certrate_array = array(
		    'certrate_name' => $certrate_name,
				'cert_type' => $cert_type,
		    'cert_amount' => $cert_amount,
		    'description' => $description,
			);
			$success = $db->update("cert_rates", $certrate_array, "certrate_id = :certrate_id", array("certrate_id" => $certrate_id));
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = unApproveAllTrips($visitor_id);
				}
			}
			
			header("Location: /certRates/update-certRate.php?certrate_id=$certrate_id&update=$success");
			exit();
		}
	} else {
		$data = $db->select("SELECT * FROM `cert_rates` WHERE `certrate_id` = :certrate_id", array( "certrate_id" => $certrate_id ));
		$data = $data[0];
		$certrate_name = $data['certrate_name'];
		$cert_type = $data['cert_type'];
		$cert_amount = $data['cert_amount'];
		$description = $data['description'];
		
		if (isCertAssigned($certrate_id)) {
			$locked = "true";
		}
		
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

			if (empty($_GET['update']) && $locked) { ?>
				<div id="Alert" class="alert alert-warning">
				   <strong>Warning!</strong> This Cert Rate has been assigned to a guide. Some fields are no longer editable.
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
					<form class="form-horizontal" action="/certRates/update-certRate.php?certrate_id=<?php echo $certrate_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Certification Rate</legend>
							<?php
								include 'cert_formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									
									<?php if (!isCertAssigned($certrate_id)) { ?>
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

									<a class="btn btn-low btn-primary" href="/certRates/certRates.php">Back</a>
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