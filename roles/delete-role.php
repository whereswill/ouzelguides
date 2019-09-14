<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Roles";

	$role_id = 0;
	
	if ( !empty($_GET['role_id'])) {
		$role_id = $_REQUEST['role_id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$role_id = $_POST['role_id'];

		// delete data
		$q = $db->delete('roles','role_id = :role_id', array( "role_id" => $role_id ));
		
		header("Location: /roles/roles.php");
		exit();
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Role</h3>
					<form class="form-horizontal" action="/roles/delete-role.php" method="post">
						<input type="hidden" name="role_id" value="<?php echo $role_id;?>"/>
						<?php
						if (!isRoleEditable($role_id)){ //add if locked
						?>
								<p class="alert alert-warning">You cannot delete this Role. This is a system Role. There are functions that will no longer work properly if this Role is deleted. Please contact the developer if this is a problem. </p>
								<div class="form-actions">
									<a class="btn btn-low btn-primary" href="/roles/roles.php">Back</a>
								</div>
						<?php
						} else if (roleLocked($role_id)){
					?>
							<p class="alert alert-warning">You cannot delete this Role. There are currently locked trip(s) with this Role. Please deactivate this Role if you no longer wish to use it. </p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/roles/roles.php">Back</a>
							</div>
						<?php
						} else if (eventsWithThisRole($role_id) > 0){ //check if includes locked
					?>
							<p class="alert alert-warning">You cannot delete this Role. There are currently <?php echo eventsWithThisRole($role_id);?> trip(s) with this Role. Please edit the Trips to change the role before deleting this Role. </p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/roles/roles.php">Back</a>
							</div>
						<?php
						} else {
						?>
							<p class="alert alert-danger">Are you sure you want to delete this role?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/roles/roles.php">No</a>
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