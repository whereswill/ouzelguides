<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Timesheets";
	
	//clear OTHER data variables
	$user_id_fk = null;

//PROCESS FORM/////////////

	if (!empty($_POST)) {
		$user_id_fk = $_POST['timesheetuser_id_fk'];

		// validate input
		$valid = true;
		if (empty($user_id_fk)) {
			$timesheetuser_id_fkError = 'Please choose a user to add work';
			$valid = false;
		}
		
		if ($valid) {
			$timesheet_array = array(
		    "user_id_fk" => "$user_id_fk",
		    "created_on" => $datetime,
		    "created_by" => "$visitor_id",
			);
			$success = $db->insert('timesheets',$timesheet_array);

			//if success, go to timesheet page

			if($success) {
				$last_id = $db->lastInsertId();
				header("Location: /timesheets/schedule-otherEvents.php?timesheet_id=" . $last_id);
				exit();
			} else {
				header("Location: /timesheets/timesheets.php");
				exit();
			}
		}
	}
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">All Timesheets</h3>
					</div>
					<div>
						<table class="table table-striped table-bordered" id="sorted_table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Dates</th>
									<th>Events</th>
									<th>Approved</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 

									$timesheets = $db->select("SELECT `timesheet_id`, `user_id_fk`, `approved_on` FROM `timesheets`");

									$user = new User;
									$timesheet = new Timesheet;
								  	foreach ($timesheets as $row) {
										//$user = new User;
										$timesheet->set_sheet_id($row['timesheet_id']);
										$user->set_user_id($row['user_id_fk']);
									  	echo '<tr>' . "\n";
										   	echo '<td>'. $user->getUserName() . '</td>' . "\n"; //User name

										   	echo '<td>'. $timesheet->getTimesheetDates() . '</td>' . "\n";
										
										   	echo '<td>'. $timesheet->getSheetCount() . '</td>' . "\n"; //Number of Events
									
										   	echo '<td>';
										 		if(!is_null($row['approved_on'])) {
													echo format_date($row['approved_on']); //Approved
												}
											echo '</td>' . "\n";

										  echo '<td width=110>' . "\n";
												echo '<div class="btn-group btn-group-xs">' . "\n";
										   		echo '<a class="btn btn-success" href="/timesheets/schedule-otherEvents.php?timesheet_id='.$row['timesheet_id'].'"><span class="glyphicon glyphicon-share-alt"></span></a>' . "\n";
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