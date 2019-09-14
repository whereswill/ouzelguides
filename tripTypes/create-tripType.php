<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Trip Types";

	if ( !empty($_POST)) {
		// keep track validation errors
		$triptype_nameError = null;
		$descriptionError = null;
		$dd_orderError = null;
		
		// keep track post values
		$triptype_name = $_POST['triptype_name'];
		$description = $_POST['description'];
		$dd_order = $_POST['dd_order'];
		
		// validate input
		$valid = true;
		if (empty($triptype_name)) {
			$triptype_nameError = 'Please enter a name for this trip type';
			$valid = false;
		}

		if (empty($dd_order)) {
			$dd_orderError = 'Please enter the order the trip should appear in drop-down selections';
			$valid = false;
		}
		
		// insert data
		if ($valid) {
			$triptype_array = array(
		    'triptype_name' => $triptype_name,
		    'description' => $description,
		    'dd_order' => $dd_order,
			);
			$q = $db->insert('trip_types',$triptype_array);
			header("Location: /tripTypes/tripTypes.php");
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
					<form class="form-horizontal" action="/tripTypes/create-tripType.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a Trip Type</legend>
							<?php
								include 'tripType_formGroup.php';
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