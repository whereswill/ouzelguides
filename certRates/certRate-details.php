<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Certifications";
	
	$certrate_id = null;
	if ( !empty($_GET['certrate_id'])) {
		$certrate_id = $_REQUEST['certrate_id'];
	}
	
	if ( null==$certrate_id ) {
		header("Location: /certRates/certRates.php");
		exit();
	} else {
		$data = $db->select("SELECT * FROM `cert_rates` WHERE `certrate_id` = :certrate_id", array( "certrate_id" => $certrate_id ));
		$data = $data[0];
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-12"> <!--start content column-->	    		
					<div class="form-horizontal" role="form">
						<div class="col-sm-12">
							<legend>Certification Rate Details</legend>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-primary pull-left btn-style-left" href="/certRates/certRates.php">Back</a>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Cert. Rate Name:</label>
								<div class="col-sm-6">
									<p><?php echo $data['certrate_name'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Per day Amount:</label>
								<div class="col-sm-6">
									<p><?php echo "$" . $data['cert_amount'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Description:</label>
								<div class="col-sm-6">
									<p><?php echo $data['description'];?></p>
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