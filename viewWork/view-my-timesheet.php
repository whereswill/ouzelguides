<?php

  include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

  //SET PERMISSIONS FOR PAGE
  // if(!$visitor->isAdmin()) {
  //   header("Location: /index.php");
  //   exit();
  //  }

  //variable to identify this page title////////
  $title = "My Timesheet";

  //INITIALIZE ALL VARIABLE///////////
  //Necessary because not every page has all variables passed in both GET and POST///

  $timesheet_id = null;

  // clear TOTAL data variables
  $other_total = null;
  $timesheet_total = null;

  // clear OTHER data variables
  $timesheeteventuser_id_fk = null;
  $o_role_id_fk = null;
  $timesheetevent_notes = null;

//GET INFO FOR timesheet DETAILS AREA//////////////////////
  
  if (!empty($_GET['timesheet_id'])) {
    $timesheet_id = $_REQUEST['timesheet_id'];
    //echo $timesheet_id;
  }
  
  if ( null==$timesheet_id) {
    header("Location: /my-work.php");
    exit();
  } else {
    //INSTANTIATE timesheet///// 
    $timesheet = new Timesheet();
    $timesheet->set_sheet_id($timesheet_id);
    
    $data = $timesheet->getTimesheetDetails();

    $user_id = $timesheet->getSheetUser();

    if ($user_id != $visitor_id && !$visitor->isAdmin()) {
      header("Location: /my-work.php");
      exit();
    }

    $user = new User();
    $user->set_user_id($user_id);
    
  }

  include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
  include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>
    
      <div class="row">
         <div class="col-sm-3 bs-docs-sidebar">
              
            <?php include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/side-nav.php'; ///Left nav box?>

          </div>
        <div class="col-sm-9">
          <h3 id="comments-title">
            Timesheet Details 
            <?php if ($timesheet->isTimesheetLocked()) {
                echo '<small class="currentR">Locked</small>';
              } else {
                echo '<small class="current">Approved</small>';
              } ?>
          </h3>
          <hr>
<!--           <div class="col-sm-12">
            <legend>Timesheet Details
              <?php if ($timesheet->isTimesheetLocked()) {
                //echo ' - <span class="currentR">Locked</span>';
              } else {
                //echo ' - <span class="current">Approved</span>';
              } ?>
            </legend>
          </div> -->

          <div class="col-sm-6"> <!--start split column-->
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">User Name:</label>
              <div class="col-sm-7">
                <p><?php echo $user->getUserName();?></p>
              </div>
            </div>
          </div> <!--end split column-->
          <div class="col-sm-6 space"> <!--start split column-->
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Created:</label>
              <div class="col-sm-7">
                <p><?php echo format_datetime($data['created_on']);?></p>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Payroll Approved:</label>
              <div class="col-sm-7">
                <p style="color:green;">
                  <?php if ( is_null($data['approved_on'])) {
                    echo "Not Approved";
                  } else {
                    echo format_date($data['approved_on']);
                  }?>
                </p>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Payroll Locked:</label>
              <div class="col-sm-7">
                <p>
                  <?php if ( is_null($data['locked_on'])) {
                    echo "Not Locked";
                  } else {
                    echo format_date($data['locked_on']);
                  }?>
                </p>
              </div>
            </div>
          </div> <!--end split column-->

          <!-- <table cellpadding="0" cellspacing="0" border="0" class="table table-striped users-table" id="sorted_table_4" width="100%"> -->
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Role</th>
                <th>Hours</th>
                <th>Rate</th>
                <th>Push Rate</th>
                <th>Assoc. Trip</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              
              $other_events = $db->select("SELECT * FROM approvals WHERE `timesheet_id_fk` = :timesheet_id ORDER BY `approval_id` ASC", array( "timesheet_id" => $timesheet_id ));
              if (count($other_events) > 0) {
              
                foreach ($other_events as $row) {
                
                  echo '<tr>' . "\n";
                
                  echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n"; //Role
                  echo '<td>' . $row['event_hours'] . '</td>' . "\n"; //Hours
                  echo '<td>';
                  if ($row['event_hours'] != NULL) {
                    echo formatMoney($row['other_pay']/$row['event_hours']);
                  }
                  echo '</td>' . "\n"; //Rate

                  $other_total = $row['other_pay'];
                  echo '<td>'. formatMoney($row['other_pay']) . ' ' . "\n"; //Push Rate
                  if (!empty($row['notes'])) {
                    echo '<div class="note_popup" title="Notes" data-placement="left" data-content="' . $row['notes'] . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div></td>' . "\n"; //Info Icon
                  }

                  echo '<td>';
                  if ($row['trip_id_fk']) {
                    $trip = new Trip();
                    $trip->set_trip_id($row['trip_id_fk']);
                    echo $trip->getTripStats();  //Assoc. Trip
                  }
                  echo'</td>' . "\n";
                
                  echo '<td>'. formatMoney($other_total) . '</td>' . "\n"; //Total
                
                  $timesheet_total = $timesheet_total + $other_total;
                }
              }
              
              echo '<tr class="total-row">' . "\n";
              echo '<td></td>' . "\n"; //Role
              echo '<td></td>' . "\n"; //Hours
              echo '<td></td>' . "\n"; //Rate
              echo '<td></td>' . "\n"; //Push Rate
              echo '<td>Timesheet Total</td>' . "\n"; //Assoc. Trip
              echo '<td>'. formatMoney($timesheet_total) . '</td>' . "\n"; //Total
              echo '</tr>' . "\n";
              ?>
            </tbody>
          </table>
        </div>
      </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; ?>

  </body>
</html>
