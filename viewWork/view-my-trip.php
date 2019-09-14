<?php

  include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

  //SET PERMISSIONS FOR PAGE
  // if(!$visitor->isAdmin()) {
  //   header("Location: /index.php");
  //   exit();
  //  }

  //variable to identify this page title////////
  $title = "My Trip";

  //INITIALIZE ALL VARIABLE///////////
  //Necessary because not every page has all variables passed in both GET and POST///

  $trip_id = null;

  // clear TOTAL data variables
  $guide_total = null;
  $other_total = null;
  $trip_total = null;

  // clear GUIDE data variables
  $user_id_fk = null;
  $g_role_id_fk = null;
  $rigger_bool = null;
  $food_shopper_bool = null;

  // clear OTHER data variables
  $tripeventuser_id_fk = null;
  $o_role_id_fk = null;
  $tripevent_notes = null;

  //GET INFO FOR TRIP DETAILS AREA//////////////////////
  
  if (!empty($_GET['trip_id'])) {
    $trip_id = $_REQUEST['trip_id'];
  }
  
  if ( null==$trip_id) {
    header("Location: /my-work.php");
    exit();
  } else {
    //INSTANTIATE TRIP///// 
    $trip = new Trip();
    $trip->set_trip_id($trip_id);

    if (!$trip->isGuideOnTrip($visitor_id) && !$visitor->isAdmin()) {
      header("Location: /my-work.php");
      exit();
    }
    
    $data = $trip->getTripDetails();
    
    $name = $trip->getTripName();
    
    $type = $trip->getTripType();
    
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
            Trip Details 
            <?php if ($trip->isTripLocked()) {
                echo '<small class="currentR">Locked</small>';
              } else {
                echo '<small class="current">Approved</small>';
              } ?>
          </h3>
          <hr>

          <div class="col-sm-6"> <!--start split column-->
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Trip Name:</label>
              <div class="col-sm-7">
                <p><?php echo $name['rivertrip_name'];?></p>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Trip Type:</label>
              <div class="col-sm-7">
                <p><?php echo $type['triptype_name'];?></p>
              </div>
            </div>  
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Satellite?:</label>
              <div class="col-sm-7">
                <?php if ($trip->isSat()) {
                  echo "<p>Satellite</p>";
                } else {
                  echo "<p>Local</p>";
                }?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Turnaround?:</label>
              <div class="col-sm-7">
                <?php if ($data['turnaround']) {
                  echo "<p>Turnaround</p>";
                } else if ($data['turnaround'] == NULL) {
                  echo "<p>Not Designated</p>";
                } else {
                  echo "<p>Fresh Pack</p>";
                }?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Put-in:</label>
              <div class="col-sm-7">
                <p><?php echo format_date($data['putin_date']);?></p>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Take-out:</label>
              <div class="col-sm-7">
                <p><?php echo format_date($data['takeout_date']);?></p>
              </div>
            </div>
          </div> <!--end split column-->
          <div class="col-sm-6 space"> <!--start split column-->
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Days:</label>
              <div class="col-sm-7">
                <p><?php echo $trip->tripDays();?></p>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Guests:</label>
              <div class="col-sm-7">
                <p><?php echo (!isset($data['guests_num'])) ? 0 : $data['guests_num'] ;?></p>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Assigned:</label>
              <div class="col-sm-7">
                <p><?php echo $trip->numberOfAssigned();?></p>
              </div>
            </div>  
            <div class="form-group">
              <label class="col-sm-5 control-label" style="padding-top:0px;">Trip Created:</label>
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

          <?php
          $guide_events = $db->select("SELECT * FROM approvals WHERE `trip_id_fk` = :trip_id ORDER BY `approval_id` ASC", array( "trip_id" => $trip_id ));
          if (count($guide_events) > 0) {
          ?>

          <!-- <table cellpadding="0" cellspacing="0" border="0" class="table table-striped users-table" id="sorted_table_4" width="100%"> -->
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Base</th>
                <th>TL</th>
                <th>Sat.</th>
                <th>Bumps</th>
                <th>Rig</th>
                <th>Shop</th>
                <th>Other</th>
                <th>Certs</th>
                <th>Total</th>
                <th>Bonus</th>
              </tr>
            </thead>
            <tbody>
            <?php
            ///////INSTANTIATE GUIDE OBJECT///////////
            $guide = new Guide();
            foreach ($guide_events as $row) {
              
              if ($visitor_id == $row['user_id_fk']) {
                $guide->set_guide_id($row['user_id_fk']);

                echo '<tr>' . "\n";

                echo '<td>';
                if ($row['timesheet_id_fk']) {
                  echo '<span class="icon-user glyphicon glyphicon-list-alt"></span> ';
                }               
                echo $guide->getUserName() . '</td>' . "\n";
              
                echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
              
                $guide_total = $row['base_pay'];
                echo '<td>'. formatMoney($row['base_pay']) . '</td>' . "\n"; //Base Pay

                $guide_total = $guide_total + $row['tl_pay'];
                echo '<td>'. formatMoney($row['tl_pay']) . '</td>' . "\n"; //TL Pay

                $guide_total = $guide_total + $row['sat_pay'];
                echo '<td>'. formatMoney($row['sat_pay']) . '</td>' . "\n"; //Sat Pay

                $guide_total = $guide_total + $row['bump_pay'];
                echo '<td>'. formatMoney($row['bump_pay']) . '</td>' . "\n"; //Bump Pay

                $guide_total = $guide_total + $row['rig_pay'];
                echo '<td>'. formatMoney($row['rig_pay']) . '</td>' . "\n"; //Rig Pay

                $guide_total = $guide_total + $row['shop_pay'];
                echo '<td>'. formatMoney($row['shop_pay']) . '</td>' . "\n"; //Shop Pay

                $guide_total = $guide_total + $row['other_pay'];
                echo '<td>'. formatMoney($row['other_pay']) . ' ' . "\n"; //Other Pay
                if (!empty($row['notes'])) {
                  echo '<div class="note_popup" title="Notes" data-placement="left" data-content="' . $row['notes'] . '"><span class="glyphicon glyphicon-info-sign" style="color:#428bca;"></span></div></td>' . "\n"; //Info Icon
                }
              
                $guide_total = $guide_total + $row['cert_pay'];
                echo '<td>'. formatMoney($row['cert_pay']) . '</td>' . "\n"; //Cert Pay
              
                echo '<td>'. formatMoney($guide_total) . '</td>' . "\n"; //Total
                
                echo '<td>'. formatMoney($row['bonus_pay']) . '</td>' . "\n"; //Bonus Pay
                echo '</tr>' . "\n";
              
                $trip_total = $trip_total + $guide_total;

              }
            }

              echo '<tr class="total-row">' . "\n";
              echo '<td></td>' . "\n";
              echo '<td></td>' . "\n";
              echo '<td></td>' . "\n"; //Base Pay
              echo '<td></td>' . "\n"; //TL Pay
              echo '<td></td>' . "\n"; //Sat Pay
              echo '<td></td>' . "\n"; //Bump Pay
              echo '<td></td>' . "\n"; //Rig Pay
              echo '<td></td>' . "\n"; //Shop Pay
              echo '<td></td>' . "\n"; //Other Pay
              echo '<td>Trip Total</td>' . "\n"; //Cert Pay
              echo '<td>'. formatMoney($trip_total) . '</td>' . "\n"; //Total
              echo '<td></td>' . "\n"; //Bonus Pay
              echo '</tr>' . "\n";
              ?>
            </tbody>
          </table>
              

          <div class="col-sm-12">
            <legend>Other Guides</legend>
          </div>
          <!-- <table cellpadding="0" cellspacing="0" border="0" class="table table-striped users-table" id="sorted_table_4" width="100%"> -->
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Rigger</th>
                <th>Shopper</th>
              </tr>
            </thead>
            <tbody>
            <?php

            foreach ($guide_events as $row) {
              
              if ($visitor_id != $row['user_id_fk'] && $row['timesheet_id_fk'] == NULL) {
                $guide->set_guide_id($row['user_id_fk']);
                $event = $db->select("SELECT `rigger_bool`, `food_shopper_bool` FROM guide_events WHERE `guideevent_id` = :guideevent_id_fk", array( "guideevent_id_fk" => $row['guideevent_id_fk'] ));
                echo '<tr>' . "\n";
                echo '<td>';
                if ($row['timesheet_id_fk']) {
                  echo '<span class="icon-user glyphicon glyphicon-list-alt"></span> ';
                }               
                echo $guide->getUserName() . '</td>' . "\n";
              
                echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
                  
                echo '<td width=100>';
                echo ($event[0]['rigger_bool'] ? '<span class="glyphicon glyphicon-ok"></span>' : '');
                echo '</td>' . "\n";
                echo '<td>';
                echo ($event[0]['food_shopper_bool'] ? '<span class="glyphicon glyphicon-ok"></span>' : '');
                echo '</td>' . "\n";
                echo '</tr>' . "\n";
              }

              if ($visitor_id != $row['user_id_fk'] && $row['timesheet_id_fk'] != NULL) {
                $guide->set_guide_id($row['user_id_fk']);
                echo '<tr>' . "\n";
                echo '<td>';
                if ($row['timesheet_id_fk']) {
                  echo '<span class="icon-user glyphicon glyphicon-list-alt"></span> ';
                }               
                echo $guide->getUserName() . '</td>' . "\n";
              
                echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n";
                  
                echo '<td width=100>';
                echo '</td>' . "\n";
                echo '<td>';
                echo '</td>' . "\n";
                echo '</tr>' . "\n";
              }

            }
            echo '</tbody>';
          echo '</table>';
          }
          ?>
        </div>
      </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; ?>

  </body>
</html>
