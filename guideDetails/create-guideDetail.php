<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Guide Details";

	if ( !empty($_POST)) {

		// print_r($_POST);
		// exit();

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
		
		// insert data
		if ($valid) {		
			$guidedetails_array = array(
			    'user_id_fk' => $user_id_fk,
			    'hire_date' => $hire_date,
			    'bonus_start' => $bonus_start,
			    'bonus_eligible' => $bonus_eligible,
			    'active_bool' => $active_bool,
			);

			// print_r($guidedetails_array);
			// exit();

			$success = $db->insert('guide_details',$guidedetails_array);
			
			header("Location: /guideDetails/update-guideDetail.php?user_id_fk=$user_id_fk&update=$success");
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
					<form class="form-horizontal" action="/guideDetails/create-guideDetail.php" method="post">
						<fieldset id="no-margin">
							<legend>Add Guide Detail</legend>
							<?php
								include 'guideDetail-formGroup.php';
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