<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Trip Types";

	$triptype_id = 0;
	
	if ( !empty($_GET['triptype_id'])) {
		$triptype_id = $_REQUEST['triptype_id'];
		
		//check for triptype_id in trips table
		$result = $db->select("SELECT COUNT(*) AS num FROM `trips` WHERE `trip_types_fk` = :r", array( "r" => $triptype_id));
        $tripsWithThisType = $result[0]['num'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$triptype_id = $_POST['triptype_id'];

		// delete data
		$q = $db->delete('trip_types','triptype_id = :triptype_id', array( "triptype_id" => $triptype_id ));
		header("Location: /tripTypes/tripTypes.php");
		exit();

	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Trip Type</h3>
					<?php
					if (!isTypeEditable($triptype_id)){ //add if locked
					?>
							<p class="alert alert-warning">You cannot delete this Trip Type. This is a system Type. There are functions that will no longer work properly if this Type is deleted. Please contact the developer if this is a problem. </p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/tripTypes/tripTypes.php">Back</a>
							</div>
					<?php
					} else if ($tripsWithThisType > 0){ 
					?>
						<form class="form-horizontal" action="/tripTypes/delete-tripType.php" method="post">
							<p class="alert alert-danger">You cannot delete this Trip Type. There are currently <?php echo $tripsWithThisType;?> trip(s) with this Trip Type. Please edit the Trips to change the trip type before deleting this Trip Type. </p>
							<div class="form-actions">
								<a class="btn btn-low btn-primary" href="/tripTypes/tripTypes.php">Back</a>
							</div>
						</form>
					<?php
					} else {
					?>
						<form class="form-horizontal" action="/tripTypes/delete-tripType.php" method="post">
							<p class="alert alert-warning">Are you sure you want to delete this Trip Type?</p>
							<div class="form-actions">
								<input type="hidden" name="triptype_id" value="<?php echo $triptype_id;?>"/>
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/tripTypes/tripTypes.php">No</a>
							</div>
						</form>
					<?php 
					}
					?>
				</div> <!--end content column-->
			</div> <!--.row-->
<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; 
?>

	</body>
</html>