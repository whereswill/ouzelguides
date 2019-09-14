<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "TL Rates";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">TL Rates</h3>
					</div>
					<div>
						<!-- <p><a href="/tlRates/create-tlRate.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Create TL Rate</a></p> -->						
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>TL Rate Name</th>
									<th>Rate Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 

									 $tlRates = $db->select("SELECT * FROM tl_rates ORDER BY `tl_amount` ASC");
									 foreach ($tlRates as $row) {
									  	echo '<tr>' . "\n";
									   	echo '<td>'. $row['tlrate_name'] . '</td>' . "\n";
									   	echo '<td>'. $row['tl_amount'] . '</td>' . "\n";
									   	echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
									   	echo '<a class="btn btn-default" href="/tlRates/tlRate-details.php?tlrate_id='.$row['tlrate_id'].'"><span class="glyphicon glyphicon-list"></span></a>' . "\n";
									   	echo '<a class="btn btn-info" href="/tlRates/update-tlRate.php?tlrate_id='.$row['tlrate_id'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
									   	echo '<a class="btn btn-danger" href="/tlRates/delete-tlRate.php?tlrate_id='.$row['tlrate_id'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
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