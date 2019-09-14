<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "All Users";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">All Users</h3>
						<!-- <p><a href="/guideDetails/create-guideDetail.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Add Guide</span></a></p> -->
					</div>
					<div>
						<table class="table table-striped table-bordered" id="sorted_table">
							<thead>
								<tr>
									<th>Active User?</th>
									<th>User Name</th>
									<th>Register Date</th>
									<th>Email Confirmed</th>
									<th>Banned?</th>
									<th>Active Guide?</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									
									$details = $db->select("SELECT * FROM `as_users` ORDER BY `user_id` DESC");
									$user = new User();
									foreach ($details as $row) {
										
										$user->set_user_id($row['user_id']);
										
								  	echo '<tr>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($user->isActiveUser() ? '<span class="glyphicon glyphicon-ok"></span>' : '');
										echo '</td>' . "\n";
										echo '<td>'. $user->getUserName() . '</td>' . "\n";
										echo '<td>'. $row['register_date'] . '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($row['confirmed'] == 'Y' ? '<span class="glyphicon glyphicon-ok"></span>' : '');
										echo '</td>' . "\n";
										//echo '<td>'. $user->isActiveGuide() . '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($row['banned'] == 'Y' ? '<span class="glyphicon glyphicon-ok"></span>' : '');
										echo '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($user->isActiveGuide() ? '<span class="glyphicon glyphicon-ok"></span>' : '');
										echo '</td>' . "\n";
								   	echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
								   	//echo '<a class="btn btn-default" href="/users/guideDetail-details.php?user_id_fk='.$row['user_id_fk'].'"><span class="glyphicon glyphicon-list"></span></a>' . "\n";
								   	echo '<a class="btn btn-info" href="/users/update-user.php?user_id_fk='.$row['user_id'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
								   	//echo '<a class="btn btn-danger" href="/users/delete-guideDetail.php?user_id_fk='.$row['user_id_fk'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
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