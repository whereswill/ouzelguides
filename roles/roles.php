<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Roles";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">Roles</h3>
					</div>
					<div>
						<p><a href="/roles/create-role.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Create Role</a></p>
						<?php $r = 1;
						while ($r <= 2) { ?>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>
										<?php echo ($r==1 ? 'Guide Roles' : 'Other Roles'); ?>
									</th>
									<th>Role Type</th>
									<?php echo ($r==2) ? "<th>Default Rate</th>" : "" ; ?>
									<!-- <th>Default Rate</th> -->
									<th>Order</th>
									<th>Active</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 

									$roles = $db->select("SELECT * FROM roles ORDER BY `role_type` ASC, `dd_order` ASC");
									foreach ($roles as $row) {
										if ($row['role_type'] == $r) {
									  	echo '<tr>' . "\n";
									   	echo '<td>'. $row['role_name'] . '</td>' . "\n";
									   	echo '<td>'. getRoleType($row['role_type']) . '</td>' . "\n";
											echo ($r==2) ? '<td>'. formatMoney($row['default_amount']) . '</td>' . "\n" : "" ;
									   	echo '<td>'. $row['dd_order'] . '</td>' . "\n";
									   	echo '<td width=100>';
									 		echo ($row['active'] == "Y" ? '<span class="glyphicon glyphicon-ok"></span>' : '');
											echo '</td>' . "\n";
									   	echo '<td width=110>' . "\n";
											echo '<div class="btn-group btn-group-xs">' . "\n";
									   	echo '<a class="btn btn-default" href="/roles/role-details.php?role_id='.$row['role_id'].'"><span class="glyphicon glyphicon-list"></span></a>' . "\n";
									   	echo '<a class="btn btn-info" href="/roles/update-role.php?role_id='.$row['role_id'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
									   	echo '<a class="btn btn-danger" href="/roles/delete-role.php?role_id='.$row['role_id'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
											echo '</div>' . "\n";
									   	echo '</td>' . "\n";
									   	echo '</tr>' . "\n";
										}
									}
								?>
							</tbody>
						</table>
						<?php $r++;
						} ?>
					</div> <!-- .row -->
				</div> <!--end content column-->
			</div> <!-- .row -->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>