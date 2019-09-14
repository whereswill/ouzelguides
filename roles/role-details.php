<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Roles";
	
	$role_id = null;
	if ( !empty($_GET['role_id'])) {
		$role_id = $_REQUEST['role_id'];
	}
	
	if ( null==$role_id ) {
		header("Location: /roles/roles.php");
		exit();
	} else {
		$data = $db->select("SELECT * FROM `roles` WHERE `role_id` = :role_id", array( "role_id" => $role_id ));
		$data = $data[0];
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-12"> <!--start content column-->	    		
					<div class="form-horizontal" role="form">
						<div class="col-sm-12">
							<legend>Role Details</legend>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-primary pull-left btn-style-left" href="/roles/roles.php">Back</a>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Role Name:</label>
								<div class="col-sm-6">
									<p><?php echo $data['role_name'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Role Type:</label>
								<div class="col-sm-6">
									<p><?php echo getRoleType($data['role_type']);?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Default Rate:</label>
								<div class="col-sm-6">
									<p><?php echo $data['default_amount'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Description:</label>
								<div class="col-sm-6">
									<p><?php echo $data['description'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Drop-down Order:</label>
								<div class="col-sm-6">
									<p><?php echo $data['dd_order'];?></p>
								</div>
							</div>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
						</div> <!--end split column-->
						<div class="col-sm-2"> <!--start split column-->
						</div> <!--end split column-->
					</div><!--form-horizontal-->
				</div> <!--end content column-->
			</div><!--.row-->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>