<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "TL Rates";
	
	$locked = null;
	$tlrate_id = null;
	
	if ( !empty($_GET['tlrate_id'])) {
		$tlrate_id = $_REQUEST['tlrate_id'];
	}
	
	if ( null==$tlrate_id ) {
		header("Location: /tlRates/tlRates.php");
		exit();
	}
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$tlrate_nameError = null;
		$satellite_boolError = null;
		$day_boolError = null;
		$tl_amountError = null;
		
		// keep track post values
		$tlrate_name = $_POST['tlrate_name'];
		$satellite_bool = $_POST['satellite_bool'];
		$day_bool = $_POST['day_bool'];
		$tl_amount = $_POST['tl_amount'];
		
		// validate input
		$valid = true;
		if (empty($tlrate_name)) {
			$tlrate_nameError = 'Please enter a name for this TL Rate';
			$valid = false;
		}

		if (empty($satellite_bool)) {
			$satellite_boolError = 'Please enter whether Satellite or Local';
			$valid = false;
		}

		if (empty($day_bool)) {
			$day_boolError = 'Please enter whether Day or Multi-day trip';
			$valid = false;
		}

		if (empty($tl_amount)) {
			$tl_amountError = 'Please enter the dollar amount for this TL Rate';
			$valid = false;
		}
	
		// update data
		if ($valid) {
			$tlrate_array = array(
			    'tlrate_name' => $tlrate_name,
			    'satellite_bool' => $satellite_bool,
			    'day_bool' => $day_bool,
			    'tl_amount' => $tl_amount,
				);
			$success = $db->update("tl_rates", $tlrate_array, "tlrate_id = :tlrate_id", array("tlrate_id" => $tlrate_id));
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = unApproveAllTrips($visitor_id);
				}
			}

			header("Location: /tlRates/update-tlRate.php?tlrate_id=$tlrate_id&update=$success");
			exit();

		}
	} else {
		$data = $db->select("SELECT * FROM `tl_rates` WHERE `tlrate_id` = :tlrate_id", array( "tlrate_id" => $tlrate_id ));
		$data = $data[0];
		$tlrate_name = $data['tlrate_name'];
		$satellite_bool = $data['satellite_bool'];
		$day_bool = $data['day_bool'];
		$tl_amount = $data['tl_amount'];
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
					<form class="form-horizontal" action="/tlRates/update-tlRate.php?tlrate_id=<?php echo $tlrate_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update TL Rate</legend>
							<?php
								include 'tlRate_formGroup.php';
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
										
									<a class="btn btn-low btn-primary" href="/tlRates/tlRates.php">Back</a>
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