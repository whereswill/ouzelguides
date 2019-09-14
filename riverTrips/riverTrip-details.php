<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "River Trips";
	
	$rivertrip_id = null;
	if ( !empty($_GET['rivertrip_id'])) {
		$rivertrip_id = $_REQUEST['rivertrip_id'];
	}
	
	if ( null==$rivertrip_id ) {
		header("Location: /riverTrips/riverTrips.php");
		exit();
	} else {
		$data = $db->select("SELECT * FROM `river_trips` WHERE `rivertrip_id` = :rivertrip_id", array( "rivertrip_id" => $rivertrip_id ));
		//print_r($data);
		$data = $data[0];
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-12"> <!--start content column-->	    		
					<div class="form-horizontal" role="form">
						<div class="col-sm-12">
							<legend>Trip Name Details</legend>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-primary pull-left btn-style-left" href="/riverTrips/riverTrips.php">Back</a>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">River Trip Name:</label>
								<div class="col-sm-6">
									<p><?php echo $data['rivertrip_name'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Long Name:</label>
								<div class="col-sm-6">
									<p><?php echo $data['longname'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Drainage:</label>
								<div class="col-sm-6">
									<p><?php echo $data['drainage'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Put-in:</label>
								<div class="col-sm-6">
									<p><?php echo $data['putin_name'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Take-out:</label>
								<div class="col-sm-6">
									<p><?php echo $data['takeout_name'];?></p>
								</div>
							</div>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Satellite or Local?:</label>
								<div class="col-sm-6">
									<?php if ($data['satellite'] == "Y") {
										echo "<p>Satellite</p>";
									} else {
										echo "<p>Local</p>";
									}?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Mileage:</label>
								<div class="col-sm-6">
									<p><?php echo $data['mileage'];?></p>
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