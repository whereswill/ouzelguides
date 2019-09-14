<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Bonuses";

	$bonusrate_id = 0;
	
	if ( !empty($_GET['bonusrate_id'])) {
		$bonusrate_id = $_REQUEST['bonusrate_id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$bonusrate_id = $_POST['bonusrate_id'];
		
		// delete data
		$q = $db->delete('bonus_rates','bonusrate_id = :bonusrate_id', array( "bonusrate_id" => $bonusrate_id ));
		
		if (isset( $_POST['override_approval'])) {
			if ( $_POST['override_approval'] == "true" && $success > 0) {
				$success = unApproveAllTrips($visitor_id);
			}
		}

		header("Location: /bonusRates/bonusRates.php");	
		exit();
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Bonus Rate</h3>
					<form class="form-horizontal" action="/bonusRates/delete-bonusRate.php" method="post">
						<input type="hidden" name="bonusrate_id" value="<?php echo $bonusrate_id;?>"/>
						
						<?php 
						if (areTripsApproved()){ //check if includes locked
						?>
							<p class="alert alert-warning">This bonus rate may exist on trips that are currently approved to pay. Updating this bonus rate will un-approve ALL trips. You will need to review the pay and approve them again. Are you sure?</p>
							<div class="form-actions">
								<input type="hidden" name="override_approval" value="true">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/bonusRates/bonusRates.php">No</a>
							</div>
						<?php
						} else {
						?>
							<p class="alert alert-danger">Are you sure you want to delete this bonus rate?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/bonusRates/bonusRates.php">No</a>
							</div>
						<?php 
						}
						?>
					</form>
				</div> <!--end content column-->
			</div> <!--.row-->
<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; 
?>

	</body>
</html>