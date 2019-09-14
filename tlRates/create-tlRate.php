<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "TL Rates";

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
		
		// insert data
		if ($valid) {		
			$tlrate_array = array(
		    'tlrate_name' => $tlrate_name,
		    'satellite_bool' => $satellite_bool,
		    'day_bool' => $day_bool,
		    'tl_amount' => $tl_amount,
			);
			$q = $db->insert('tl_rates',$tlrate_array);
			header("Location: /tlRates/tlRates.php");
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
					<form class="form-horizontal" action="/tlRates/create-tlRate.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a TL Rate</legend>
							<?php
								include 'tlRate_formGroup.php';
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