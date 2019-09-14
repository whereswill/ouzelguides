<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Swamper Days";

	/////Instantiate objects/////////
	$guide = new Guide;
	$trip = new Trip;

	$this_year = $date->format("Y");
	if (isset($_POST['years'])) {
	  if (empty($_POST['years'])) {
	    $yearsError = 'Please choose a year';
	    $valid = false;
	  }
	  $this_year = $_POST['years'];
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

	///Get list of users on approved trips and timesheets
	$swampers = $guide->getSwampers($this_year);
	?>

<!-- PRINT SUMMARY SECTION -->

			<div class="row">
				<div class="col-sm-3 hidden-print"> <!--start sidebar column-->
				</div> <!--end sidebar column-->

				<div class="col-sm-6"> <!-- start content column -->
					<div class="text-center">
						<h4>Ouzel Outfitters Current Swamper Days Summary</h4>
						<p class="visible-print-block">for year: <?php echo $this_year;?></p>
					</div>
          <form class="hidden-print form-inline text-center" action="/reports/swamper-days.php" method="post">
              <div class="form-group <?php echo !empty($yearsError)?'has-error':'';?>">
                <?php $years = getYears(); ?>
                <label for="years">for year: </label>
                <select name="years" id="select-years" class="form-control input-sm" style="width: 150px;">
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
              <button type="submit" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Go</span></button>
          </form>
					<table class="table table-striped table-print">
						<thead>
							<tr>
								<th>Name</th>
								<th>Swamper Trips</th>
							</tr>
						</thead>
						<tbody>
							<?php 		

							foreach ($swampers as $swamper) {

								$guide->set_guide_id($swamper['user_id_fk']);

								$trips = $guide->getSwamperDays($this_year);

								$river_days = 1;

								////Print out total row for each guide
								echo '<tr>' . "\n";
								echo '<td>' . $guide->getUserName() . '</td>' . "\n"; //Name
								echo '<td>';
								foreach ($trips as $tr) {
									echo $trip->getTripNameID($tr['river_trips_fk']) . " - " . $tr['count'] . "<br />";
								}
								echo '</td>' . "\n"; //Trip Name
						   	echo '</tr>' . "\n";
								
							} //End for each
							?>
						</tbody>
					</table>
				</div> <!--end content column-->
			</div><!-- .row -->
			<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
		?>

	</body>
</html>