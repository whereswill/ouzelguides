<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Pay Rates";
	
	//INITIALIZE ALL VARIABLE///////////
	//Necessary because not every page has all variables passed in both GET and POST///
	$payrate_id = null;
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
		$payrate_id_fkError = null;
		$notesError = null;
		
		// keep track post values
		$user_id_fk = $_POST['user_id_fk'];
		$payrate_id = $_POST['payrate_id'];
		$notes = $_POST['notes'];
		
		// validate input
		$valid = true;
		if (empty($user_id_fk)) {
			$user_id_fkError = 'Please enter a name for this role';
			$valid = false;
		}
		
		if (empty($payrate_id)) {
			$payrate_idError = 'Please enter a type for this role';
			$valid = false;
		}
		
		// insert data
		if ($valid) {
			$payrate_array = array(
			    "user_id_fk" => "$user_id_fk",
				"payrate_id_fk" => "$payrate_id",
			    "notes" => "$notes",
			    "created_by" => "$visitor_id",
				);
			$success = $q = $db->insert('guide_payrates',$payrate_array);
			
			if (isset($_POST['override_approval'])) {
				if ( isset($_POST['override_approval']) && $_POST['override_approval'] == "true" && $success > 0) {
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
					<form class="form-horizontal" action="/guideDetails/add-payrate.php?user_id_fk=<?php echo $user_id_fk; ?>" method="post">
						<fieldset>
							<legend>Add a Pay Rate for <?php echo $guide->getUserName(); ?></legend>
							<div class="form-group <?php echo !empty($payrate_idError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Pay Rate</label>
								<div class="col-sm-6">
									<?php
									$payrates = $db->select("SELECT `payrate_id`,`rate` FROM `pay_rates` WHERE `active` = TRUE");
									?>
									<select name="payrate_id" id="select-payrate" class="form-control" style="width: 100%;">
										<option value="" default selected>Select a Rate</option>
										<?php foreach($payrates as $payrate) { ?>
											<option value="<?php echo $payrate['payrate_id']; ?>"<?php if($payrate_id == $payrate['payrate_id']) echo ' selected';?>>
												<?php echo htmlentities($payrate['rate']); ?>
											</option>
										<?php } ?>
									</select>
									<?php if (!empty($payrate_idError)): ?>
										<span class="help-inline"><?php echo $payrate_idError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($notesError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Notes</label>
								<div class="col-sm-6">
									<input name="notes" type="text" class="form-control" placeholder="Notes" value="<?php echo !empty($notes)?$notes:'';?>">
									<?php if (!empty($notesError)): ?>
										<span class="help-inline"><?php echo $notesError;?></span>
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