<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Certifications";
	
	//INITIALIZE ALL VARIABLE///////////
	//Necessary because not every page has all variables passed in both GET and POST///
	$certrate_id = null;
	$user_id_fk = null;
	
	if ( !empty($_GET['user_id_fk'])) {
		$user_id_fk = $_REQUEST['user_id_fk'];
		
		//INSTANTIATE GUIDE CLASS///////////
		$guide = new Guide();
		$guide->set_guide_id($user_id_fk);
	}

	if ( !empty($_POST)) {		
		// keep track validation errors
		$user_id_fkError = null;
		$certrate_id_fkError = null;
		$exp_dateError = null;
		
		// keep track post values
		$user_id_fk = $_POST['user_id_fk'];
		$certrate_id = $_POST['certrate_id'];
		$exp_date = $_POST['exp_date'];
		
		// validate input
		$valid = true;
		if (empty($user_id_fk)) {
			$user_id_fkError = 'Please enter a name for this role';
			$valid = false;
		}
		
		if (empty($certrate_id)) {
			$certrate_idError = 'Please enter a type for this role';
			$valid = false;
		}

		if (empty($exp_date)) {
			$exp_dateError = 'Please enter an expiration date for this certification';
			$valid = false;
		}
		
		// insert data
		if ($valid) {
			$cert_array = array(
			    "user_id_fk" => "$user_id_fk",
					"certrate_id_fk" => "$certrate_id",
			    "exp_date" => "$exp_date",
				);
			$success = $q = $db->insert('guide_certs',$cert_array);
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = $guide->unApproveGuide($visitor_id);
				}
			}
			
			header("Location: /guideDetails/update-guideDetail.php?user_id_fk=$user_id_fk");
			exit();
		}
	}
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
?>

		<div class="container">  <!--this tag closes in footer-->
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->	
					<form class="form-horizontal" action="/guideDetails/add-cert.php?user_id_fk=<?php echo $user_id_fk; ?>" method="post">
						<fieldset>
							<legend>Add a Certification for <?php echo $guide->getUserName(); ?></legend>
							<div class="form-group <?php echo !empty($certrate_idError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Certification</label>
								<div class="col-sm-6">
									<?php
									$certs = $db->select("SELECT `certrate_id`,`certrate_name` FROM `cert_rates`");
									?>
									<select name="certrate_id" id="select-cert-name" class="form-control" style="width: 100%;">
										<option value="" default selected>Select a Certification</option>
										<?php foreach($certs as $cert) { ?>
											<option value="<?php echo $cert['certrate_id']; ?>"<?php if($certrate_id == $cert['certrate_id']) echo ' selected';?>>
												<?php echo htmlentities($cert['certrate_name']); ?>
											</option>
										<?php } ?>
									</select>
									<?php if (!empty($certrate_idError)): ?>
										<span class="help-inline"><?php echo $certrate_idError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($exp_dateError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Expiration Date</label>
								<div class="col-sm-6">
									<input name="exp_date" type="date" class="form-control" placeholder="Exp. Date" value="<?php echo !empty($exp_date)?$exp_date:'';?>">
									<?php if (!empty($exp_dateError)): ?>
										<span class="help-inline"><?php echo $exp_dateError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<?php if (!$guide->isOnApproved()) { ?>
											<input type="hidden" name="user_id_fk" value="<?php echo $user_id_fk; ?>">
											<button type="submit" class="btn btn-warning">Create</button>
									<?php } else { ?>
										<input type="hidden" name="override_approval" value="true">
										<input type="hidden" name="user_id_fk" value="<?php echo $user_id_fk; ?>">
										<button  type="submit" class="btn btn-warning" data-toggle="modal" data-target="#approvalModal">Create</button>
										<?php
											include $_SERVER['DOCUMENT_ROOT'].'/guideDetails/guideDetail-updateModal.php';
									} ?>
									<a class="btn btn-primary" href="<?php echo $previous?>">Back</a>
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