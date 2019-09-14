<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Bonuses";

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
			$bonusrate_nameError = 'Please enter a name for this bonus type';
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
		
		// insert data
		if ($valid) {
			$bonusrate_array = array(
			    "bonusrate_name" => "$bonusrate_name",
			    "num_years" => "$num_years",
			    "bonus_amount" => "$bonus_amount",
				);
			$q = $db->insert('bonus_rates',$bonusrate_array);
			header("Location: /bonusRates/bonusRates.php");
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
					<form class="form-horizontal" action="/bonusRates/create-bonusRate.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a Bonus Rate</legend>
							<?php
								include 'bonus_formGroup.php';
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