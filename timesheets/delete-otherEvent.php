<?php 

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "WH Work";
	
	$otherevent_id = 0;
	
	if ( !empty($_GET['otherevent_id'])) {
		$otherevent_id = $_REQUEST['otherevent_id'];
		$timesheet_id = $_REQUEST['timesheet_id'];
	}
	
	if ( !empty($_POST)) {
		
		// keep track post values
		$otherevent_id = $_POST['otherevent_id'];
		$timesheet_id = $_REQUEST['timesheet_id'];
		
		//if timesheet is approved, un-approve before deleting
		if (!empty($_POST['override_approval'])) {
			$unapproved = unApproveTimesheet($timesheet_id, $visitor_id);
		}
		
		// delete data
		$q = $db->delete('other_events','otherevent_id = :otherevent_id', array( "otherevent_id" => $otherevent_id ));

		header("Location: /timesheets/timesheets.php");
		exit();
	} 

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
?>

			<div class="row">
				<div class="col-sm-3"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-6"> <!--start content column-->
					<h3>Delete WH Work</h3>
					<?php 
					
					//INSTANTIATE TIMESHEET///// 
					$timesheet = new Timesheet();
					$timesheet->set_sheet_id($timesheet_id);
					
					if ($timesheet->isTimesheetLocked()) { ?>
						<form class="form-horizontal" action="/timesheets/delete-otherEvent.php" method="post">
							<p class="alert alert-danger">This timesheet has already been paid and locked! You cannot delete this event.</p>
							<div class="form-actions">
								<a class="btn btn-primary" href="/timesheets/timesheets.php">Back</a>
							</div>
						</form>
					<?php
					} else if ($timesheet->isTimesheetApproved()) {
					?>
						<form class="form-horizontal" action="/timesheets/delete-otherEvent.php" method="post">
							<p class="alert alert-warning">This timesheet is currently approved to pay. Deleting this event will un-approve this timesheet and you will have to review pay and approve again. Do you still want to proceed?</p>
							<div class="form-actions">
								<input type="hidden" name="otherevent_id" value="<?php echo $otherevent_id;?>"/>
								<input type="hidden" name="timesheet_id" value="<?php echo $timesheet_id;?>"/>
								<input type="hidden" name="override_approval" value="true"/>
								<button type="submit" class="btn btn-danger">Yes</button>
								<a class="btn btn-primary" href="/timesheets/timesheets.php">No</a>
							</div>
						</form>
					<?php
					} else { 
					?>
						<form class="form-horizontal" action="/timesheets/delete-otherEvent.php" method="post">
							<p class="alert alert-danger">Are you sure you want to delete this WH Work?</p>
							<div class="form-actions">
								<input type="hidden" name="otherevent_id" value="<?php echo $otherevent_id;?>"/>
								<input type="hidden" name="timesheet_id" value="<?php echo $timesheet_id;?>"/>
								<button type="submit" class="btn btn-danger">Yes</button>
								<a class="btn btn-primary" href="/timesheets/timesheets.php">No</a>
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