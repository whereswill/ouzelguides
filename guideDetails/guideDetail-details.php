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
	if ( !empty($_GET['user_id_fk'])) {
		$user_id_fk = $_REQUEST['user_id_fk'];
	}
	
	if ( null==$user_id_fk ) {
		header("Location: /guideDetails/guideDetails.php");
		exit();
	} else {
		$data = $db->select("SELECT * FROM `guide_details` WHERE `user_id_fk` = :user_id_fk LIMIT 1", array( "user_id_fk" => $user_id_fk ));
		$data = $data[0];
		$guide = new Guide();
		$guide->set_guide_id($data['user_id_fk']);
		
		$pay_rates = $guide->getGuidePayrates();

		$name = $guide->getUserName();
		
		$cert = $guide->getGuideCerts();
		
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-7"> <!--start content column-->	    		
					<div class="form-horizontal" role="form">
						<legend>Guide Details</legend>

						<div class="form-group">
							<label class="col-sm-3 control-label" style="padding-top:0px;">Guide Name:</label>
							<div class="col-sm-9">
								<p><?php echo $name;?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" style="padding-top:0px;">Hire Date:</label>
							<div class="col-sm-9">
								<p><?php echo format_date($data['hire_date']);?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" style="padding-top:0px;">Number of Seasons:</label>
							<div class="col-sm-9">
								<p><?php echo $guide->guideYears();?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" style="padding-top:0px;">Bonus Start Date:</label>
							<div class="col-sm-9">
								<p><?php echo format_date($data['bonus_start']);?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" style="padding-top:0px;">Bonus Eligible?:</label>
							<div class="col-sm-9">
								<?php if ($data['bonus_eligible'] == "Y") {
									echo "<p>Yes</p>";
								} else {
									echo "<p>No</p>";
								}?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" style="padding-top:0px;">Active?:</label>
							<div class="col-sm-9">
								<?php if ($data['active_bool'] == "Y") {
									echo "<p>Yes</p>";
								} else {
									echo "<p>No</p>";
								}?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" style="padding-top:0px;">Pay Rate:</label>
							<div class="col-sm-9">
								<?php if($pay_rates) {
									foreach($pay_rates as $rate) {
										echo '<p>' . $rate['rate'] . ' active date:  '. format_date($rate['created_on']) . "  ";
										echo $rate['current']?'<span class="current glyphicon glyphicon-chevron-left"></span>':'';
										echo '</p>';	
									}
								} else {
									echo '<p>No Pay Rates</p>';
								}						
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" style="padding-top:0px;">Certifications:</label>
							<div class="col-sm-9">
								<?php if($cert) {
									foreach($cert as $cert) {
										echo '<p>' . $cert['certrate_name'] . ' expires: '. format_date($cert['exp_date']) . "  ";
										echo $cert['current']?'<span class="current glyphicon glyphicon-chevron-left"></span>':'';
										echo '</p>';	
									}
								} else {
									echo '<p>No certifications</p>';
								}						
								?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<a class="btn btn-primary pull-left btn-style-left" href="/guideDetails/guideDetails.php">Back to Guides</a>
							</div>
						</div> <!--end form-group-->
					</div><!--form-horizontal-->
				</div> <!--end content column-->
				<div class="col-sm-5"> <!--start content column-->	
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
	                <em> at <?php echo $note['created_on'] . ($note['public'] =='Y' ? ' (public)' : ' (private)'); ?></em>
			          </small>
			        </blockquote>
			        <?php endforeach; ?>
				    </div>
					</div>
				</div>
			</div><!--.row-->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>