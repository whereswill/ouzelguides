<?php
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

//variable to identify this page title
$title = "My Work";

///////INSTANTIATE OBJECTS////////
$trip = new Trip();
$guide = new Guide();
$timesheet = new Timesheet();
$guide->set_guide_id($visitor_id);

$this_year = $date->format("Y");
$valid = true;
$print = false;

if (isset($_POST['log_user_id'])) {
  // validate input
  if (empty($_POST['log_user_id'])) {
    $log_user_idError = 'Please choose a guide';
    $valid = false;
  }
  $guide->set_guide_id($_POST['log_user_id']);
}
if (isset($_POST['years'])) {
  if (empty($_POST['years'])) {
    $yearsError = 'Please choose a year';
    $valid = false;
  }
  $this_year = $_POST['years'];
}

$trips = $guide->getLoggedTrips($this_year);
$timesheets = $guide->getLockedWH($this_year);
$table_title = "Paid Work for " . $guide->getUserName() . " - " . $this_year;

if (count($trips) > 0) {
  $print = true;
}

include 'OGtemplates/header.php';
include 'OGtemplates/top-nav.php';
?>
        
    <div class="row">
      <div class="col-sm-3 bs-docs-sidebar hidden-print">
        
        <?php
        ///Left nav box
        include 'OGtemplates/side-nav.php';
        ?>

      </div>
      <div class="col-sm-9">
        <div class="row">
          <div class="col-sm-6">
            <h3 id="comments-title">
            <?php echo $table_title; ?>
            </h3>
            <form class="hidden-print" action="/my-work.php" method="post">
              <fieldset>
                <?php if ($visitor->isAdmin()) { ?>
                  <div class="form-group <?php echo !empty($log_user_idError)?'has-error':'';?>">
                    <?php
                    $others = $guide->getActiveUsers();
                    ?>
                    <select name="log_user_id" id="select-user-name" class="form-control" autofocus="autofocus">
                      <option value="" default selected>Select a name</option>
                      <?php foreach($others as $other) { ?>
                        <option value="<?php echo $other['user_id']; ?>"<?php echo ($other['user_id'] == $guide->get_guide_id( )) ? " selected" : "" ; ?>>
                          <?php echo htmlentities($other['name']); ?>
                        </option>
                      <?php } ?>
                    </select>
                    <?php if (!empty($log_user_idError)): ?>
                      <span class="help-inline"><?php echo $log_user_idError;?></span>
                    <?php endif; ?>
                  </div>
                <?php } ?>
                <div class="form-group <?php echo !empty($yearsError)?'has-error':'';?>">
                  <?php
                  $years = getYears();
                  ?>
                  <select name="years" id="select-years" class="form-control">
                    <?php foreach($years as $year) { ?>
                      <option value="<?php echo $year; ?>"<?php echo ($year == $this_year) ? " selected" : "" ; ?>>
                        <?php echo htmlentities($year); ?>
                      </option>
                    <?php } ?>
                  </select>
                  <?php if (!empty($yearsError)): ?>
                    <span class="help-inline"><?php echo $yearsError;?></span>
                  <?php endif; ?>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Get My Work</span></button>
                </div>  
              </fieldset>
            </form>
          </div>
        </div>

        <!-- Print log -->

        <div class="row">
          <div class="col-sm-12">
          <?php 
          if ($print) { 
            $guide_id = $guide->get_guide_id(); ?>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped users-table" id="sorted_table_4" width="100%">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Worksheet Name</th>
                  <th style="display:none;">Sort Dates</th>
                  <th>Event Dates</th>
                  <th>Assigned</th>
                  <th>Approved On</th>
                  <th>Paid On</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                <?php 
                  foreach ($trips as $row) {
                    //Set trip id///// 
                    $trip->set_trip_id($row['trip_id_fk']);
                    
                    echo '<tr>' . "\n";
                    echo '<td class="text-center"><span class="icon-user glyphicon glyphicon-road"></span></td>' . "\n"; //Type
                    echo '<td>'. $trip->getTripNameType() . '</td>' . "\n";
                    echo '<td style="display:none;">' . $trip->getSortDate() . '</td>' . "\n";
                    echo '<td>'. $trip->getTripDates() . '</td>' . "\n";
                    echo '<td>'. $trip->numberOfAssigned() . ' assigned</td>' . "\n";
                    echo '<td>' . format_date($trip->isApproved()) . '</td>' . "\n";
                    echo '<td>';
                    if($trip->isTripLocked()) {
                      echo format_date($trip->isTripLocked());
                    } else {
                      echo  '<span class="glyphicon glyphicon-remove icon-danger"></span>';
                    }
                    echo '</td>' . "\n";
                    echo '<td width=110>' . "\n";
                    echo '<div class="btn-group btn-group-xs">' . "\n";
                    if (!$visitor->isAdmin()) {
                      $page = '/viewWork/view-my-trip.php';
                    } else {
                      $page = '/scheduleTrips/schedule-trip.php';
                    }
                    echo '<a class="btn btn-success"  href="'.$page.'?trip_id='.$row['trip_id_fk'].'"><span class="glyphicon glyphicon-share-alt"></span></a>' . "\n";
                    echo '</div>' . "\n";
                    echo '</td>' . "\n";
                    echo '</tr>' . "\n";
                  }

                  foreach ($timesheets as $row) {
                    //set timesheet id///// 
                    $timesheet->set_sheet_id($row['timesheet_id_fk']);
                    $sheetUser = $timesheet->getSheetUser();
                    $user = new User();
                    $user->set_user_id($sheetUser);
                    
                    echo '<tr>' . "\n";
                    echo '<td class="text-center"><span class="icon-user glyphicon glyphicon-list-alt"></span></td>' . "\n"; //Type
                    echo '<td>'. $user->getUserName() . '</td>' . "\n";
                    echo '<td style="display:none;">' . $timesheet->getSortDate() . '</td>' . "\n";
                    echo '<td>'. $timesheet->getTimesheetDates() . '</td>' . "\n";
                    echo '<td>'. $timesheet->getSheetCount() . ' events</td>' . "\n";
                    echo '<td>' . format_date($timesheet->isApproved()) . '</td>' . "\n";
                    echo '<td>';
                    if($timesheet->isTimesheetLocked()) {
                      echo format_date($timesheet->isTimesheetLocked());
                    } else {
                      echo  '<span class="glyphicon glyphicon-remove icon-danger"></span>';
                    }
                    echo '</td>' . "\n";
                    echo '<td width=110>' . "\n";
                    echo '<div class="btn-group btn-group-xs">' . "\n";
                    if (!$visitor->isAdmin()) {
                      $page = '/viewWork/view-my-timesheet.php';
                    } else {
                      $page = '/timesheets/schedule-otherEvents.php';
                    }
                    echo '<a class="btn btn-success"  href="'.$page.'?timesheet_id='.$row['timesheet_id_fk'].'"><span class="glyphicon glyphicon-share-alt"></span></a>' . "\n";
                    echo '</div>' . "\n";
                    echo '</td>' . "\n";
                    echo '</tr>' . "\n";
                  }
                ?>
              </tbody>
            </table>
          <?php
          } else {
            echo "<p><br />There are is paid work for this user.</p>";
          } ?>
        </div>
        </div> <!-- end .row -->
      </div> <!-- end col-sm-9 -->
    </div> <!-- end .row -->
        
    <?php include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; ?>
    
  </body>
</html>
