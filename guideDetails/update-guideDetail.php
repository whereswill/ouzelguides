<?php 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Guide Details";

	$user_id_fk = null;
	$success = null;

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
		// keep track validation errors
		$user_id_fkError = null;
		$hire_dateError = null;
		$bonus_startError = null;
		$bonus_eligibleError = null;
		$active_boolError = null;
		
		// keep track post values
		$user_id_fk = $_POST['user_id_fk'];
		$hire_date = $_POST['hire_date'];
		$bonus_start = $_POST['bonus_start'];
		$bonus_eligible = $_POST['bonus_eligible'];
		$active_bool = $_POST['active_bool'];
		
		// validate input
		$valid = true;
		if (empty($user_id_fk)) {
			$user_id_fkError = 'Please enter a guide name';
			$valid = false;
		}
		
		if (empty($hire_date)) {
			$hire_dateError = 'Please enter a hire date';
			$valid = false;
		}
		
		if (empty($bonus_start)) {
			$bonus_startError = 'Please enter a bonus start date';
			$valid = false;
		}
		
		if (empty($bonus_eligible)) {
			$bonus_eligibleError = 'Please check whether guide is eligible';
			$valid = false;
		} 

		if (empty($active_bool)) {
			$active_boolError = 'Please check whether guide is Active';
			$valid = false;
		}
		
		// update data
		if ($valid) {
			$guide_array = array(
			    'user_id_fk' => $user_id_fk,
			    'hire_date' => $hire_date,
			    'bonus_start' => $bonus_start,
			    'bonus_eligible' => $bonus_eligible,
			    'active_bool' => $active_bool,
				);
			$success = $db->update("guide_details", $guide_array, "user_id_fk = :user_id_fk", array( "user_id_fk" => $user_id_fk));
			
			if (isset( $_POST['override_approval'])) {
				if ( $_POST['override_approval'] == "true" && $success > 0) {
					$success = $guide->unApproveGuide($visitor_id);
				}
			}
	
			header("Location: /guideDetails/update-guideDetail.php?user_id_fk=$user_id_fk&update=$success");
			exit();
			
		}
	} else {
		$data = $db->select("SELECT * FROM `guide_details` WHERE `user_id_fk` = :user_id_fk", array( "user_id_fk" => $user_id_fk ));
		$data = $data[0];
		$cert = $guide->getGuideCerts();
		$pay_rate = $guide->getGuidePayrates();
		$user_id_fk = $data['user_id_fk'];
		$hire_date = $data['hire_date'];
		$bonus_start = $data['bonus_start'];
		$active_bool =  $data['active_bool'];
		$bonus_eligible =  $data['bonus_eligible'];
	
		if ($guide->isOnLocked()) {
			$locked = "true";
		}
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

			if ( !empty($_GET['update'])) {
				if ($_GET['update'] > 0) { ?>
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
				<div class="col-sm-7"> <!--start content column-->	
					<form class="form-horizontal" action="/guideDetails/update-guideDetail.php?user_id_fk=<?php echo $user_id_fk; ?>" method="post">
						<fieldset id="no-margin">
							<legend>Update Guide Details</legend>
							<?php
								include 'guideDetail-formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<?php if (!$guide->isOnApproved()) { ?>
										<button type="submit" class="btn btn-low btn-info">Update</button>
									<?php } else { ?>
										<input type="hidden" name="override_approval" value="true">
										<button  type="submit" class="btn btn-low btn-info" data-toggle="modal" data-target="#approvalModal">Update</button>
										<?php
											include $_SERVER['DOCUMENT_ROOT'].'/guideDetails/guideDetail-updateModal.php';
									} ?>
									<a class="btn btn-low btn-primary" href="/guideDetails/guideDetails.php">Back</a>
								</div>
							</div> <!--end form-group-->

							<?php if(!empty($_GET)) {?>
								<div class="form-group">
									<label class="col-sm-3 control-label btn-group-sm"><a href="/guideDetails/add-payrate.php?user_id_fk=<?php echo $user_id_fk; ?>" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Add Pay Rate</a></label>
									<div class="col-sm-6">
										<?php if(isset($pay_rate) && !$pay_rate == false) {
											foreach($pay_rate as $pay_rate) {
												echo '<p class="control-label pull-left" style="clear:left;">' . $pay_rate['rate'] . ' date active:  '. format_date($pay_rate['created_on']) . '</p>';	
											}
										} else {
											echo '<p class="control-label pull-left" style="clear:left;">No Pay Rates</p>';
										}						
										?>
									</div>
								</div>
							<?php } 
							if(!empty($_GET)) {?>
								<div class="form-group">
									<label class="col-sm-3 control-label btn-group-sm"><a href="/guideDetails/add-cert.php?user_id_fk=<?php echo $user_id_fk; ?>" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Add Certification</a></label>
									<div class="col-sm-6">
										<?php if(isset($cert) && !$cert == false) {
											foreach($cert as $cert) {
												echo '<p class="control-label pull-left" style="clear:left;">' . $cert['certrate_name'] . ' exp. date:  '. format_date($cert['exp_date']) . " " . '<a style="color:red;" href="/guideDetails/delete-cert.php?guidecert_id=' . $cert['guidecert_id'] . '&user_id_fk=' . $user_id_fk . '"> <span class="glyphicon glyphicon-remove"></span></a></p>';	
											}
										} else {
											echo '<p class="control-label pull-left" style="clear:left;">No certifications</p>';
										}						
										?>
									</div>
								</div>
							<?php } ?>

							<?php if($visitor->getRole() != 'user'): ?>
							<div class="leave-comment form-group">
								<!-- <label class="col-sm-3 control-label">Post Guide Note</label> -->
								<button class="col-sm-3 control-label btn-group-sm" id="note"><span class="glyphicon glyphicon-plus btn btn-warning btn-style"> Post Note</span></button>
								<div class="col-sm-9">
									<div class="control-group">
								    <div class="_controls">
								      <textarea class="form-control" id="note-text"></textarea>
								    	<input type="checkbox" name="is_public" id="public_box"> OK for guide see this comment?<br>
								    </div>
									</div>
								</div>
							</div>
							<?php else: ?>
							<p>You can't post</p>
							<?php endif; ?>
						</fieldset>
					</form>
				</div> <!--end content column-->

				<!--start Notes right side column-->	
				<div class="col-sm-5"> 
					<div class="notes">
						<legend>Guide Notes</legend>
				    <div class="notes-notes">
			        <?php $userNotes = new Note(); ?>
			        <?php $notes = $userNotes->getUserNotes($user_id_fk); ?>
			        <?php foreach($notes as $note): ?>
			        <blockquote>
		            <p><?php echo htmlentities( stripslashes($note['user_note']) ); ?></p>
		            <small>
	                <?php echo htmlentities($note['posted_by_name']);  ?> 
	                <em> at <?php echo $note['created_on'] . ($note['public'] =='Y' ? ' (public)' : ' (private)') . "   " . '</em><span style="color:red;" data-note-id="' . $note['usernotes_id'] . '" class="glyphicon glyphicon-remove delete-note" aria-hidden="true"></span>'; ?>
			          </small>
			        </blockquote>
			        <?php endforeach; ?>
				    </div>
					</div>
				</div>
				
			</div><!--.row-->

	<?php	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; ?>

  <script src="/OGLibrary/js/ogengine.js" type="text/javascript" charset="utf-8"></script>
	<?php	include $_SERVER['DOCUMENT_ROOT'].'/OGLibrary/js/user_notes.php'; ?>

	</body>
</html>