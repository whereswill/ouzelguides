<?php
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

//variable to identify this page title
$title = "My River Log";

///////INSTANTIATE OBJECTS////////
$trip = new Trip();
$guide = new Guide();
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
$table_title = "River Log for " . $guide->getUserName() . " - " . $this_year;

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
            <form class="hidden-print" action="/river-log.php" method="post">
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
                  <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Get River Log</span></button>
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
            <table class="table table-print">
              <thead>
                <tr>
                  <th>Trip</th>
                  <th>Role</th>
                  <th>River Days</th>
                  <th>River Miles</th>
                  <th>Notes</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $days_total = 0;
                $miles_total = 0;
                ////Print out row for each event
                foreach ($trips as $row) {
                  if (isGuideRole($row['role_id_fk'])) {
                    $trip->set_trip_id($row['trip_id_fk']);

                    echo '<tr>' . "\n";
                    echo '<td>' . $trip->getTripStats() . '</td>' . "\n"; //Trip
                    echo '<td>'. getRoleName($row['role_id_fk']) . '</td>' . "\n"; //Role

                    $trip_days = $trip->riverDays($row['trip_id_fk'], $guide_id);
                    $river_days += $trip_days;

                    echo '<td>'. $trip_days . '</td>' . "\n"; //River Days
                    $miles = $trip->getMiles();
                    echo '<td>'. $miles . '</td>' . "\n"; //River Miles
                      $miles_total += $miles;
                    echo '<td>None</td>' . "\n"; //Notes
                  }
                }
                ////Print out total row
                echo '<tr>' . "\n";
                echo '<td colspan="2"></td>' . "\n"; //Blank
                echo '<td><strong>'. $river_days . '</strong></td>' . "\n"; //Total Days
                echo '<td><strong>'. $miles_total . '</strong></td>' . "\n"; //Bonus Miles
                echo '<td></td>' . "\n";
                echo '</tr>' . "\n";
                ?>
              </tbody>
            </table>
          <?php
          } else {
            echo "<p><br />There are no paid trips for this user.</p>";
          } ?>
        </div>
        </div> <!-- end .row -->
      </div> <!-- end col-sm-9 -->
    </div> <!-- end .row -->
        
    <?php include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; ?>
    
  </body>
</html>
