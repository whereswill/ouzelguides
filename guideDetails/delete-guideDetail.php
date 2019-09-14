<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Guide Details";

	//INITIALIZE ALL VARIABLE///////////
	//Necessary because not every page has all variables passed in both GET and POST///
	$user_id_fk = null;
	$trip_id = 0;

	if ( !empty($_GET['user_id_fk'])) {
		$user_id_fk = $_REQUEST['user_id_fk'];
		
		//INSTANTIATE GUIDE CLASS///////////
		$guide = new Guide();
		$guide->set_guide_id($user_id_fk);
	}
	
	if ( null==$user_id_fk ) {
		header("Location: /guideDetails/guideDetails.php");
		exit();
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$user_id_fk = $_POST['user_id_fk'];
		
		if ($guide->isOnLocked()) {
			header("Location: /guideDetails/guideDetails.php");
			exit();
		}
		
		if ($guide->isOnApproved()) {
			$success = $guide->unApproveGuide($visitor_id);
		}

		// delete from any guide schedules
		$q = $db->deleteAll('guide_events','user_id_fk = :user_id_fk', array( "user_id_fk" => $user_id_fk ));
		// delete from any trip help
		$q = $db->deleteAll('other_events','user_id_fk = :user_id_fk', array( "user_id_fk" => $user_id_fk ));
		// delete from any certs
		$q = $db->deleteAll('guide_certs','user_id_fk = :user_id_fk', array( "user_id_fk" => $user_id_fk ));
		// delete guide
		$q = $db->delete('guide_details','user_id_fk = :user_id_fk', array( "user_id_fk" => $user_id_fk ));

		header("Location: /guideDetails/guideDetails.php");
		exit();
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete Guide<?php //echo "for" . $user_id_fk;?></h3>
					<form class="form-horizontal" action="/guideDetails/delete-guideDetail.php?user_id_fk=<?php echo $user_id_fk; ?>" method="post">
						<?php
						if ($guide->isOnLocked() > 0) {
							?>
							<p class="alert alert-warning">This guide has already been paid and is on trips that are locked. You can no longer delete this guide. Please de-activate this guide in the update screen to remove this guide from drop-downs.</p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/guideDetails/guideDetails.php">Back</a>
							</div>
							<?php
						} elseif ($guide->isOnApproved() > 0) {
							?>
							<input type="hidden" name="override_approval" value="approved"/>
							<p class="alert alert-danger">This guide is on trip(s) that are currently approved to pay. Deleting this guide will also remove them from any currently scheduled or approved trips and un-approve those trips. You will need to review the pay and approve the trip again. Do you still want to proceed?</p>
							<div class="form-actions">
								<input type="hidden" name="user_id_fk" value="<?php echo $user_id_fk;?>"/>
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/guideDetails/guideDetails.php">No</a>
							</div>
							<?php
						} elseif ($guide->isOnAssigned() > 0){ 
						?>
							<input type="hidden" name="override_approval" value="scheduled"/>
							<p class="alert alert-danger">This guide is currently scheduled on <?php echo $guide->isOnAssigned();?> trip(s). Deleting this guide will also remove them from any currently scheduled trips. Do you still want to proceed?</p>
							<div class="form-actions">
								<input type="hidden" name="user_id_fk" value="<?php echo $user_id_fk;?>"/>
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/guideDetails/guideDetails.php">No</a>
							</div>
						<?php
						} else {
						?>
							<p class="alert alert-danger">Are you sure you want to delete this Guide?</p>
							<div class="form-actions">
								<input type="hidden" name="user_id_fk" value="<?php echo $user_id_fk;?>"/>
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/guideDetails/guideDetails.php">No</a>
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