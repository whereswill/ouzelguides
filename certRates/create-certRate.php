<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Certifications";

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

		if (empty($cert_amount)) {
			$cert_amountError = 'Please enter the dollar amount for this certification level';
			$valid = false;
		}

		if (empty($description)) {
			$descriptionError = 'Please enter a description of this certification level';
			$valid = false;
		}
		
		// insert data
		if ($valid) {
			$certrate_array = array(
		    'certrate_name' => $certrate_name,
				'cert_type' => $cert_type,
		    'cert_amount' => $cert_amount,
		    'description' => $description,
			);
			$q = $db->insert('cert_rates',$certrate_array);
			header("Location: /certRates/certRates.php");
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
					<form class="form-horizontal" action="/certRates/create-certRate.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a Certification Rate</legend>
							<?php
								include 'cert_formGroup.php';
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