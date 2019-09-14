<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Rig Rates";

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
			$turnaround_boolError = 'Please enter whether rig is a fresh pack or a turnaround';
			$valid = false;
		}

		if (empty($rig_amount)) {
			$rig_amountError = 'Please enter the dollar amount for this Rig Rate';
			$valid = false;
		}
		
		// insert data
		if ($valid) {		
			$rigrate_array = array(
			    'rigrate_name' => $rigrate_name,
			    'satellite_bool' => $satellite_bool,
			    'turnaround_bool' => $turnaround_bool,
			    'rig_amount' => $rig_amount,
				);
			$q = $db->insert('rig_rates',$rigrate_array);
			header("Location: /rigRates/rigRates.php");
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
					<form class="form-horizontal" action="/rigRates/create-rigRate.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a Rig Rate</legend>
							<?php
								include 'rigRate_formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<button type="submit" class="btn btn-low btn-warning">Create</button>
									<a class="btn btn-low btn-primary" href="<?php echo $previous?>">Back</a>
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