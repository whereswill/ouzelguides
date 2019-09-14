<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Timesheets";

	$timesheet_id = 0;
	
	if (!empty($_GET['timesheet_id']) || !empty($_POST)) {
		$timesheet_id = $_REQUEST['timesheet_id'];
	} else {
		header("Location: /timesheets/timesheets.php");	
		exit();
	}

	//INSTANTIATE TIMESHEET///// 
	$timesheet = new Timesheet();
 	$timesheet->set_sheet_id($timesheet_id);
		
	//IF TIMESHEET IS LOCKED, REDIRECT TO PAY TIMESHEETS/////////////////
	if ($timesheet->isTimesheetLocked()) {
		header("Location: /payTimesheets/pay-timesheet.php?timesheet_id=".$timesheet_id);
		exit();
	}
	
	//DELETE TIMESHEET AND ALL EVENTS////////////
	if (!empty($_POST)) {
		
		//if timesheet is approved, un-approve before deleting
		if (!empty($_POST['override_approval'])) {
			$unapproved = unApproveTimesheet($timesheet_id, $visitor_id);
		}
		// delete others
		$q = $db->deleteAll('other_events','timesheet_id_fk = :timesheet_id', array( "timesheet_id" => $timesheet_id ));
		// delete timesheet
		$q = $db->delete('timesheets','timesheet_id = :timesheet_id', array( "timesheet_id" => $timesheet_id ));

		header("Location: /timesheets/timesheets.php");
		exit();		
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete a Timesheet</h3>
					<?php
					if ($timesheet->isTimesheetApproved()) { 
					?>
						<form class="form-horizontal" action="/timesheets/delete-timesheet.php" method="post">
							<input type="hidden" name="timesheet_id" value="<?php echo $timesheet_id;?>"/>
							<input type="hidden" name="override_approval" value="true"/>
							<p class="alert alert-warning">This timesheet is currently approved to pay. Deleting this timesheet will remove anyone assigned to this timesheet and remove the timesheet from the approved queue. Do you still want to proceed?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/timesheets/timesheets.php">No</a>
							</div>
						</form>
					<?php
					} elseif ($timesheet->getSheetCount() > 0){ 
					?>
						<form class="form-horizontal" action="/timesheets/delete-timesheet.php" method="post">
							<input type="hidden" name="timesheet_id" value="<?php echo $timesheet_id;?>"/>
							<p class="alert alert-warning">There are currently <?php echo $timesheet->getSheetCount();?> events scheduled on this timesheet. Deleting this timesheet will also remove all events assigned to this timesheet and unapprove any trips associated with this timesheet. Do you still want to proceed?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/timesheets/timesheets.php">No</a>
							</div>
						</form>
					<?php
					} else {
					?>
						<form class="form-horizontal" action="/timesheets/delete-timesheet.php" method="post">
							<input type="hidden" name="timesheet_id" value="<?php echo $timesheet_id;?>"/>
							<p class="alert alert-warning">Are you sure you want to delete this timesheet?</p>
							<div class="form-actions">
								<button type="submit" class="btn btn-low btn-danger">Yes</button>
								<a class="btn btn-low btn-primary" href="/timesheets/timesheets.php">No</a>
							</div>
						</form>	
					<?php 
					}
					?>
				</div> <!--end content column-->
			</div> <!--.row-->
<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; 
?>

	</body>
</html>