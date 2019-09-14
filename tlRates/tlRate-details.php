<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "TL Rates";
	
	$tlrate_id = null;
	if ( !empty($_GET['tlrate_id'])) {
		$tlrate_id = $_REQUEST['tlrate_id'];
	}
	
	if ( null==$tlrate_id ) {
		header("Location: /tlRates/tlRates.php");
		exit();
	} else {
		$data = $db->select("SELECT * FROM `tl_rates` WHERE `tlrate_id` = :tlrate_id", array( "tlrate_id" => $tlrate_id ));
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
							<legend>TL Rates</legend>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-primary pull-left btn-style-left" href="/tlRates/tlRates.php">Back</a>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">TL Rate Name:</label>
								<div class="col-sm-6">
									<p><?php echo $data['tlrate_name'];?></p>
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
								<label class="col-sm-6 control-label" style="padding-top:0px;">Day-trip or Multi-day?:</label>
								<div class="col-sm-6">
									<?php if ($data['day_bool'] == "Y") {
										echo "Day-trip";
									} else {
										echo "Multi-day";
									}?>
								</div>
							</div>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Per day Amount:</label>
								<div class="col-sm-6">
									<p><?php echo $data['tl_amount'];?></p>
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