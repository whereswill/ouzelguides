<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Pay Rates";

	if ( !empty($_POST)) {
		// keep track validation errors
		$payrate_nameError = null;
		$rateError = null;
		$descriptionError = null;
		
		// keep track post values
		$payrate_name = $_POST['payrate_name'];
		$rate = $_POST['rate'];
		$description = $_POST['description'];
		
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
		
		// insert data
		if ($valid) {
			$payrate_array = array(
		    'payrate_name' => $payrate_name,
		    'rate' => $rate,
		    'description' => $description,
			);
			$q = $db->insert('pay_rates',$payrate_array);
			header("Location: /payRates/payRates.php");
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
					<form class="form-horizontal" action="/payRates/create-payRate.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a Pay Rate</legend>
							<?php
								include 'payRate_formGroup.php';
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