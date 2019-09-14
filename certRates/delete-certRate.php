<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Certifications";

	$certrate_id = 0;
	
	if ( !empty($_GET['certrate_id'])) {
		$certrate_id = $_REQUEST['certrate_id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$certrate_id = $_POST['certrate_id'];
		
		// delete data
		$q = $db->delete('cert_rates','certrate_id = :certrate_id', array( "certrate_id" => $certrate_id ));

		header("Location: /certRates/certRates.php");
		exit();	
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Certification Rate</h3>
					<form class="form-horizontal" action="/certRates/delete-certRate.php" method="post">
						<input type="hidden" name="certrate_id" value="<?php echo $certrate_id;?>"/>
						
						<?php 
						if (isCertAssigned($certrate_id)){
						?>
							<p class="alert alert-warning">You cannot delete this Certification Rate. This certification rate is assigned to a guide(s) and is current. You will need to remove this rate from the guides to whom it is assigned before deleting.</p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/certRates/certRates.php">Back</a>
							</div>
						<?php
						} else {
						?>
							<p class="alert alert-danger">Are you sure you want to delete this certification rate?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/certRates/certRates.php">No</a>
							</div>
						<?php 
						}
						?>

						</div>
					</form>
				</div> <!--end content column-->
			</div> <!--.row-->
<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; 
?>

	</body>
</html>