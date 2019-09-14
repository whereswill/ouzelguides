<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Roles";

	if ( !empty($_POST)) {
		// keep track validation errors
		$role_nameError = null;
		$role_typeError = null;
		$rateError = null;
		$descriptionError = null;
		$dd_orderError = null;
		
		// keep track post values
		$role_name = $_POST['role_name'];
		$role_type = $_POST['role_type'];
		$rate = $_POST['rate'];
		$description = $_POST['description'];
		$dd_order = $_POST['dd_order'];
		
		// validate input
		$valid = true;
		if (empty($role_name)) {
			$role_nameError = 'Please enter a name for this role';
			$valid = false;
		}
		
		if (empty($role_type)) {
			$role_typeError = 'Please enter a type for this role';
			$valid = false;
		}

		if (empty($dd_order)) {
			$dd_orderError = 'Please enter the order the role should appear in drop-down selections';
			$valid = false;
		}
		
		// insert data
		if ($valid) {
			$role_array = array(
			  'role_name' => $role_name,
				'role_type' => $role_type,
				'default_amount' => $rate,
			  'description' => $description,
			  'dd_order' => $dd_order,
			);
			$q = $db->insert('roles',$role_array);
			header("Location: /roles/roles.php");
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
					<form class="form-horizontal" action="/roles/create-role.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a Role</legend>
							<?php
								include 'role_formGroup.php';
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