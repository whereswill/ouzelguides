<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Pay Rates";
	
	$payrate_id = 0;
	
	if ( !empty($_GET['payrate_id'])) {
		$payrate_id = $_REQUEST['payrate_id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$payrate_id = $_POST['payrate_id'];
		
		// delete data
		$q = $db->delete('pay_rates','payrate_id = :payrate_id', array( "payrate_id" => $payrate_id ));

		header("Location: /payRates/payRates.php");
		exit();	
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Pay Rate</h3>
					<form class="form-horizontal" action="/payRates/delete-payRate.php" method="post">
						<input type="hidden" name="payrate_id" value="<?php echo $payrate_id;?>"/>
						
						<?php
						if (guidesWithThisRate($payrate_id) > 0){
						?>
							<p class="alert alert-warning">You cannot delete this Pay Rate. There are currently guide(s) with this Rate. Please deactivate this rate and create a replacement instead. </p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/payRates/payRates.php">Back</a>
							</div>
						<?php
						} else {
						?>
							<p class="alert alert-danger">Are you sure you want to delete this pay rate?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/payRates/payRates.php">No</a>
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