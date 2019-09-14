<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Bonuses";
	
	$bonusrate_id = null;
	if ( !empty($_GET['bonusrate_id'])) {
		$bonusrate_id = $_REQUEST['bonusrate_id'];
	}
	
	if ( null==$bonusrate_id ) {
		header("Location: /bonusRates/bonusRates.php");
		exit();
	} else {
		$data = $db->select("SELECT * FROM `bonus_rates` WHERE `bonusrate_id` = :bonusrate_id", array( "bonusrate_id" => $bonusrate_id ));
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
							<legend>Bonus Rate Details</legend>
						</div>
						<div class="col-sm-2"> <!--start split column-->
							<a class="btn btn-low btn-primary pull-left btn-style-left" href="/bonusRates/bonusRates.php">Back</a>
						</div> <!--end split column-->
						<div class="col-sm-4"> <!--start split column-->
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Bonus Rate Name:</label>
								<div class="col-sm-6">
									<p><?php echo $data['bonusrate_name'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Years of Service:</label>
								<div class="col-sm-6">
									<p><?php echo $data['num_years'];?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-top:0px;">Per day Amount:</label>
								<div class="col-sm-6">
									<p><?php echo formatMoney($data['bonus_amount']);?></p>
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