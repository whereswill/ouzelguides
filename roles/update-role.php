<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Roles";
	
	$locked = null;

	$role_id = null;
	if ( !empty($_GET['role_id'])) {
		$role_id = $_REQUEST['role_id'];
	}
	
	if ( null==$role_id ) {
		header("Location: /roles/roles.php");
		exit();
	}
	
	if ( !empty($_POST)) {
		// keep track validation errors
		$role_nameError = null;
		$role_typeError = null;
		$rateError = null;
		$descriptionError = null;
		$dd_orderError = null;
		$active_Error = null;
		
		// keep track post values
		$role_name = $_POST['role_name'];
		$role_type = $_POST['role_type'];
		$rate = $_POST['rate'];
		$description = $_POST['description'];
		$dd_order = $_POST['dd_order'];
		$active = $_POST['active'];
		
		// validate input
		$valid = true;
		if (empty($role_name)) {
			$role_nameError = 'Please enter a name for this trip role';
			$valid = false;
		}

		if (empty($dd_order)) {
			$dd_orderError = 'Please enter the order the role should appear in drop-down selections';
			$valid = false;
		}

		if (empty($active)) {
			$activeError = 'Please check whether role is Active';
			$valid = false;
		}
		
		// update data
		if ($valid) {
			$role_array = array(
			  'role_name' => $role_name,
				'role_type' => $role_type,
				'default_amount' => $rate,
			  'description' => $description,
			  'dd_order' => $dd_order,
			  'active' => $active,
				);
			$success = $db->update("roles", $role_array, "role_id = :role_id", array("role_id" => $role_id));
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = unApproveRole($role_id, $visitor_id);
				}
			}

			header("Location: /roles/update-role.php?role_id=$role_id&update=$success");
			exit();
		}
	} else {
		$data = $db->select("SELECT * FROM `roles` WHERE `role_id` = :role_id", array( "role_id" => $role_id ));
		$data = $data[0];
		$role_name = $data['role_name'];
		$role_type = $data['role_type'];
		$rate = $data['default_amount'];
		$description = $data['description'];
		$dd_order = $data['dd_order'];
		$active = $data['active'];

		if (roleLocked($role_id) || !isRoleEditable($role_id)) {
			$locked = "true";
		}
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

			if (!isRoleEditable($role_id)){ //add if locked	?>
				<div id="Alert" class="alert alert-warning">
				   <strong>Warning!</strong> This is a system Role. Some fields are no longer editable.
				</div>
			<?php } else if (empty($_GET['update']) && $locked) { ?>
				<div id="Alert" class="alert alert-warning">
				   <strong>Warning!</strong> There is locked trips using this role. Some fields are no longer editable.
				</div>
			<?php }

			if ( !empty($_GET['update'])) {
				if ($_GET['update'] == 1) { ?>
					<div id="Alert" class="alert alert-success">
					   <strong>Success!</strong> The update was successful.
					</div>
				<?php } else { ?>
					<div id="Alert" class="alert alert-warning">
					   <strong>Warning!</strong> There was a problem with your update.
					</div>
				<?php } 
			} ?>

			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->	
					<form class="form-horizontal" action="/roles/update-role.php?role_id=<?php echo $role_id?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Role</legend>
							<?php
								include 'role_formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<?php if (!isRoleApproved($role_id)) { ?>
										<button type="submit" class="btn btn-low btn-info">Update</button>
									<?php } else { ?>
										<input type="hidden" name="override_approval" value="true">
										<button  type="submit" class="btn btn-low btn-info" data-toggle="modal" data-target="#approvalModal">Update</button>
									<?php } ?>	
									
										<!-- Update Modal for role that is on an approved trip-->
										<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										  <div class="modal-dialog">
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
										        <h4 class="modal-title" id="myModalLabel">This Role is being used on approved trips!</h4>
										      </div>
										      <div class="modal-body">
										        <p>This role has been used on trips that are currently approved to pay. Updating this role will un-approve these trips. You will need to review the pay and approve them again.</p>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-low btn-default" data-dismiss="modal">Close</button>
										        <button type="submit" class="btn btn-low btn-info">Update</button>
										      </div>
										    </div>
										  </div>
										</div>  <!--end modal-->
										
									<a class="btn btn-low btn-primary" href="/roles/roles.php">Back</a>
								</div>
							</div>  <!--end form-group-->
						</fieldset>
					</form>
				</div> <!--end content column-->
			</div><!--.row-->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>