<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Certifications";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">Certification Rates</h3>
					</div>
					<div>
						<p><a href="/certRates/create-certRate.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Create Certification</a></p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Cert. Rate Name</th>
									<th>Counts as</th>
									<th>Per-day Amount</th>
									<th>Description</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 

									 $certRates = $db->select("SELECT * FROM cert_rates ORDER BY `certrate_name` ASC");
									  foreach ($certRates as $row) {
									  	echo '<tr>' . "\n";
									   	echo '<td>'. $row['certrate_name'] . '</td>' . "\n";
									   	echo '<td>';
											if ($row['cert_type'] == "fa") {
												echo 'FA';
											} elseif ($row['cert_type'] == "cpr") {
												echo 'CPR';
											} else {
												echo 'Other';
											}
									 	echo '</td>' . "\n";
									   	echo '<td>'."$". $row['cert_amount'] . '</td>' . "\n";
									   	echo '<td>'. $row['description'] . '</td>' . "\n";
									   	echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
									   	echo '<a class="btn btn-default" href="/certRates/certRate-details.php?certrate_id='.$row['certrate_id'].'"><span class="glyphicon glyphicon-list"></span></a>' . "\n";
									   	echo '<a class="btn btn-info" href="/certRates/update-certRate.php?certrate_id='.$row['certrate_id'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
									   	echo '<a class="btn btn-danger" href="/certRates/delete-certRate.php?certrate_id='.$row['certrate_id'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
										echo '</div>' . "\n";
									   	echo '</td>' . "\n";
									   	echo '</tr>' . "\n";
									 }
								?>
							</tbody>
						</table>
					</div> <!-- .row -->
				</div> <!--end content column-->
			</div> <!-- .row -->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>