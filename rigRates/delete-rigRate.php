<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Rig Rates";

	$rigrate_id = 0;
	
	if ( !empty($_GET['rigrate_id'])) {
		$rigrate_id = $_REQUEST['rigrate_id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$rigrate_id = $_POST['rigrate_id'];
		
		// delete data
		$q = $db->delete('rig_rates','rigrate_id = :rigrate_id', array( "rigrate_id" => $rigrate_id ));

		header("Location: /rigRates/rigRates.php");	
		exit();
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Rig Rate</h3>
					<form class="form-horizontal" action="/rigRates/delete-rigRate.php" method="post">
						<p class="alert alert-warning">You cannot delete this Rate. All Guide Rig Rates are system rates. There are functions that will no longer work properly if this rate is deleted. Please contact the developer if this is a problem. </p>
						<div class="form-actions">
							<a class="btn btn-low btn-primary" href="/rigRates/rigRates.php">Back</a>
						</div>
					</form>
				</div> <!--end content column-->
			</div> <!--.row-->
<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; 
?>

	</body>
</html>