<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Trip Types";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">Trip Types</h3>
					</div>
					<div>
						<p>
							<a href="/tripTypes/create-tripType.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Create Trip Type</a>
						</p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Trip Type Name</th>
									<th>Order</th>
									<th>Active?</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 

									$tripTypes = $db->select("SELECT * FROM trip_types ORDER BY `dd_order` ASC");
								  foreach ($tripTypes as $row) {
								  	echo '<tr>' . "\n";
								   	echo '<td>'. $row['triptype_name'] . '</td>' . "\n";
								   	echo '<td>'. $row['dd_order'] . '</td>' . "\n";
								   	echo '<td width=100>';
								 		echo ($row['active'] == "Y" ? '<span class="glyphicon glyphicon-ok"></span>' : '');
										echo '</td>' . "\n";
								   	echo '<td width=110>' . "\n";
										echo '<div class="btn-group btn-group-xs">' . "\n";
								   	echo '<a class="btn btn-default" href="/tripTypes/tripType-details.php?triptype_id='.$row['triptype_id'].'"><span class="glyphicon glyphicon-list"></span></a>' . "\n";
								   	echo '<a class="btn btn-info" href="/tripTypes/update-tripType.php?triptype_id='.$row['triptype_id'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
								   	echo '<a class="btn btn-danger" href="/tripTypes/delete-tripType.php?triptype_id='.$row['triptype_id'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
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