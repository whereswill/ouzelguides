<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Bonuses";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">Bonus Rates</h3>
					</div>
					<div>
						<p><a href="/bonusRates/create-bonusRate.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Create Bonus Rate</a></p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Bonus Rate Name</th>
									<th>Years of Service</th>
									<th>Bonus Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 

									 $bonusRates = $db->select("SELECT * FROM bonus_rates ORDER BY `num_years` ASC");
									  foreach ($bonusRates as $row) {
									  	echo '<tr>' . "\n";
									   	echo '<td>'. $row['bonusrate_name'] . '</td>' . "\n";
									   	echo '<td>'. $row['num_years'] . '</td>' . "\n";
									   	echo '<td>'. $row['bonus_amount'] . '</td>' . "\n";
									   	echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
									   	echo '<a class="btn btn-default" href="/bonusRates/bonusRate-details.php?bonusrate_id='.$row['bonusrate_id'].'"><span class="glyphicon glyphicon-list"></span></a>' . "\n";
									   	echo '<a class="btn btn-info" href="/bonusRates/update-bonusRate.php?bonusrate_id='.$row['bonusrate_id'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
									   	echo '<a class="btn btn-danger" href="/bonusRates/delete-bonusRate.php?bonusrate_id='.$row['bonusrate_id'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
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