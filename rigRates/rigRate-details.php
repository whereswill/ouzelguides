<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Rig Rates";
	
	$rigrate_id = null;
	if ( !empty($_GET['rigrate_id'])) {
		$rigrate_id = $_REQUEST['rigrate_id'];
	}
	
	if ( null==$rigrate_id ) {
		header("Location: /rigRates/rigRates.php");
		exit();
	} else {
		$data = $db->select("SELECT * FROM `rig_rates` WHERE `rigrate_id` = :rigrate_id", array( "rigrate_id" => $rigrate_id ));
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
							<legend>Rig Rate Details</legend>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-low btn-primary pull-left btn-style-left" href="/rigRates/rigRates.php">Back</a>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Rig Rate Name:</label>
								<div class="col-sm-6">
									<p><?php echo $data['rigrate_name'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Satellite or Local?:</label>
								<div class="col-sm-6">
									<?php if ($data['satellite_bool'] == "Y") {
										echo "Satellite";
									} else {
										echo "Local";
									}?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">T/A or Fresh Rig?:</label>
								<div class="col-sm-6">
									<?php if ($data['turnaround_bool'] == "Y") {
										echo "Turnaround";
									} else {
										echo "Fresh Pack";
									}?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Per rig Amount:</label>
								<div class="col-sm-6">
									<p><?php echo $data['rig_amount'];?></p>
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