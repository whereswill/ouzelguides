<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

  //variable to identify this page title
	$title = "Pay Rates";

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div><!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">Pay Rates</h3>
					</div>
					<div>
						<p>
							<a href="/payRates/create-payRate.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Create Pay Rate</a>
						</p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Pay Rate Name</th>
									<th>Rate</th>
									<th>Active</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$payRates = $db->select("SELECT * FROM pay_rates ORDER BY `rate` ASC");
								  foreach ($payRates as $row) {
								  	echo '<tr>' . "\n";
								   	echo '<td>'. $row['payrate_name'] . ' ' . "\n";
										if (!empty($row['description'])) {
											echo '<div class="note_popup" title="Description" data-placement="right" data-content="' . $row['description'] . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div></td>' . "\n"; //Info Icon
										}
								   	echo '<td>'. $row['rate'] . '</td>' . "\n";
								   	echo '<td width=100>';
								 		echo ($row['active'] == "Y" ? '<span class="glyphicon glyphicon-ok"></span>' : '');
										echo '</td>' . "\n";
								   	echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
								   	echo '<a class="btn btn-default" href="/payRates/payRate-details.php?payrate_id='.$row['payrate_id'].'"><span class="glyphicon glyphicon-list"></span></a>' . "\n";
								   	echo '<a class="btn btn-info" href="/payRates/update-payRate.php?payrate_id='.$row['payrate_id'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
								   	echo '<a class="btn btn-danger" href="/payRates/delete-payRate.php?payrate_id='.$row['payrate_id'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
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