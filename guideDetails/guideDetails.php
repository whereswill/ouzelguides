<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Guide Details";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
<!-- 					<div>
						<p><a href="/guideDetails/create-guideDetail.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Add Guide</span></a></p>
					</div> -->
					<div>
						<table class="table table-striped table-bordered" id="sorted_table">
							<thead>
								<tr>
									<th>Guide Name</th>
									<th>Pay Rate</th>
									<th>Hire Date</th>
									<th>Bonus</th>
									<th>Certs</th>
									<th>Active?</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									
									$details = $db->select("SELECT * FROM `guide_details` ORDER BY `user_id_fk` DESC");
									$guide = new Guide();
									foreach ($details as $row) {
										
										$guide->set_guide_id($row['user_id_fk']);
										
								  	echo '<tr>' . "\n";
										echo '<td>'. $guide->getUserName() . '</td>' . "\n";
								   	echo '<td>'. $guide->getCurrentPayrate() . '</td>' . "\n";
								   	echo '<td>'. format_date($row['hire_date']) . '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($row['bonus_eligible'] == 'Y' ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '');
										echo '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($guide->areCertsCurrent() ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '');
										echo '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($row['active_bool'] == 'Y' ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '');
										echo '</td>' . "\n";
								   	echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
								   	echo '<a class="btn btn-default" href="/guideDetails/guideDetail-details.php?user_id_fk='.$row['user_id_fk'].'"><span class="glyphicon glyphicon-list"></span></a>' . "\n";
								   	echo '<a class="btn btn-info" href="/guideDetails/update-guideDetail.php?user_id_fk='.$row['user_id_fk'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
								   	echo '<a class="btn btn-danger" href="/guideDetails/delete-guideDetail.php?user_id_fk='.$row['user_id_fk'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
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