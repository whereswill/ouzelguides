<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "TL Rates";

	$tlrate_id = 0;
	
	if ( !empty($_GET['tlrate_id'])) {
		$tlrate_id = $_REQUEST['tlrate_id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$tlrate_id = $_POST['tlrate_id'];
		
		// delete data
		$q = $db->delete('tl_rates','tlrate_id = :tlrate_id', array( "tlrate_id" => $tlrate_id ));

		header("Location: /tlRates/tlRates.php");	
		exit();
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a TL Rate</h3>
					<form class="form-horizontal" action="/tlRates/delete-tlRate.php" method="post">
						<p class="alert alert-warning">You cannot delete this Rate. All TL Rates are system rates. There are functions that will no longer work properly if this rate is deleted. Please contact the developer if this is a problem. </p>
						<div class="form-actions">
							<a class="btn btn-low btn-primary" href="/tlRates/tlRates.php">Back</a>
						</div>
					</form>
				</div> <!--end content column-->
			</div> <!--.row-->
<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; 
?>

	</body>
</html>