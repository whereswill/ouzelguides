<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Guide Certifications";

	$guidecert_id = null;

	if ( !empty($_GET['user_id_fk'])) {
		$guidecert_id = $_REQUEST['guidecert_id'];
		$user_id_fk = $_REQUEST['user_id_fk'];
		
		//INSTANTIATE GUIDE CLASS///////////
		$guide = new Guide();
		$guide->set_guide_id($user_id_fk);
		
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$guidecert_id = $_POST['guidecert_id'];
		$user_id_fk = $_POST['user_id_fk'];

		$success = $q = $db->delete('guide_certs','guidecert_id = :guidecert_id', array( "guidecert_id" => $guidecert_id ));
		
		if (isset($_POST['override_approval'])) {
			if ( $_POST['override_approval'] == "true" && $success > 0) {
				$success = $guide->unApproveGuide($visitor_id);
			}
		}

		header("Location: /guideDetails/update-guideDetail.php?user_id_fk=$user_id_fk");
		exit();	
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete Guide Certification</h3>
					<form class="form-horizontal" action="/guideDetails/delete-cert.php?user_id_fk=<?php echo $user_id_fk; ?>" method="post">
						<input type="hidden" name="guidecert_id" value="<?php echo $guidecert_id;?>"/>
						<input type="hidden" name="user_id_fk" value="<?php echo $user_id_fk;?>"/>
						<?php 
						if ($guide->isOnApproved()){ 
						?>
							<input type="hidden" name="override_approval" value="true"/>
							<p class="alert alert-warning">This guide is currently scheduled on a trip(s) that is approved to pay. Deleting this cert will remove the trip from the approved queue. Do you still want to proceed?</p>
						<?php
						} else {
						?>
							<p class="alert alert-warning">Are you sure you want to delete this Certification?</p>
						<?php 
						}
						?>
						<div class="form-actions">
							<button type="submit" class="btn btn-danger">Yes</button>
							<a class="btn btn-primary" href="/guideDetails/update-guideDetail.php?user_id_fk=<?php echo $user_id_fk;?>">No</a>
						</div>
					</form>	
				</div> <!--end content column-->
			</div> <!--.row-->
<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; 
?>

	</body>
</html>