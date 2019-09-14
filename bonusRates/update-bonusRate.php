<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Bonuses";

	$bonusrate_id = null;
	
	if ( !empty($_GET['bonusrate_id'])) {
		$bonusrate_id = $_REQUEST['bonusrate_id'];
	}
	
	if ( null==$bonusrate_id ) {
		header("Location: /bonusRates/bonusRates.php");
		exit();
	}
	
	//print_r($_POST);
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$bonusrate_nameError = null;
		$num_yearsError = null;
		$bonus_amountError = null;
		
		// keep track post values
		$bonusrate_name = $_POST['bonusrate_name'];
		$num_years = $_POST['num_years'];
		$bonus_amount = $_POST['bonus_amount'];
		
		// validate input
		$valid = true;
		if (empty($bonusrate_name)) {
			$bonusrate_nameError = 'Please enter a name for this trip type';
			$valid = false;
		}

		if (empty($num_years)) {
			$num_yearsError = 'Please enter the number of years that this bonus level requires';
			$valid = false;
		}

		if (empty($bonus_amount)) {
			$bonus_amountError = 'Please enter the dollar amount for this bonus level';
			$valid = false;
		}
	
		// update data
		if ($valid) {
			$bonusrate_array = array(
			    "bonusrate_name" => "$bonusrate_name",
			    "num_years" => "$num_years",
			    "bonus_amount" => "$bonus_amount",
				);
			$success = $db->update("bonus_rates", $bonusrate_array, "bonusrate_id = :bonusrate_id", array("bonusrate_id" => $bonusrate_id));
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = unApproveAllTrips($visitor_id);
				}
			}
			
			header("Location: /bonusRates/bonusRates.php?bonusrate_id=$bonusrate_id&update=$success");
			exit();
		}
	} else {
		$data = $db->select("SELECT * FROM `bonus_rates` WHERE `bonusrate_id` = :bonusrate_id", array( "bonusrate_id" => $bonusrate_id ));
		$data = $data[0];
		$bonusrate_name = $data['bonusrate_name'];
		$num_years = $data['num_years'];
		$bonus_amount = $data['bonus_amount'];
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

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
					<form class="form-horizontal" action="/bonusRates/update-bonusRate.php?bonusrate_id=<?php echo $bonusrate_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Bonus Rate</legend>
							<?php
								include 'bonus_formGroup.php';
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
									
									<a class="btn btn-low btn-primary" href="/bonusRates/bonusRates.php">Back</a>
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