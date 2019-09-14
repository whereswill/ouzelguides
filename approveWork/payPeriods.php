<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Pay Periods";

 	///////INSTANTIATE OBJECTS////////
 	$user = new User();

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">Pay Periods</h3>
						<p>Below are the groups of locked events and the end date (pay period) that was chosen when they were locked.</p>
					</div>
					<div>
						<table class="table table-striped table-bordered" id="sorted_table_desc">
							<thead>
								<tr>
									<th>Locked On</th>
									<th>End Date</th>
									<th>Locked By</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$groups = $db->select("SELECT * FROM `lock_dates` ORDER BY `end_date` ASC");
								foreach ($groups as $row) {
									//Set user id///// 
									$user->set_user_id($row['locked_by']);
									
							  	echo '<tr>' . "\n";
							   	echo '<td>'. format_datetime($row['lock_date']) . '</td>' . "\n";
							   	echo '<td>'. format_date($row['end_date']) . '</td>' . "\n";
							   	echo '<td>'. $user->getUserName($row['locked_by']) . '</td>' . "\n";
							   	echo '<td width=110>' . "\n";
									echo '<div class="btn-group btn-group-xs">' . "\n";
							   	echo '<a class="btn btn-success" href="/approveWork/view-locked.php?lock_date='.$row['lock_date'].'&end_date='.$row['end_date'].'"><span class="glyphicon glyphicon-share-alt"></span></a>' . "\n";
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

	<script> $('#approvalModal').modal('show');</script>

	<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
	?>

    <script type="text/javascript">
      $(document).ready(function() {

				$('#sorted_table_desc').dataTable( {
				  "columnDefs": [
				    { "orderable": false, "targets": -1 }
				  ],
				  "order": [ 1, 'desc' ]
				} ); 

      } );
  	</script>

	</body>
</html>