<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "River Trip Pay";

	/////Instantiate objects/////////
	$guide = new Guide;
	$riverTrip = new RiverTrip;

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
	$drainages = $riverTrip->getDrainages();
	?>

<!-- PRINT SUMMARY SECTION -->

			<div class="row">

				<div class="col-sm-3 hidden-print"> <!--start sidebar column-->
				</div> <!--end sidebar column-->

				<div class="col-sm-6"> <!-- start content column -->
					<div class="text-center">
						<h4>Ouzel Outfitters Ratio by Drainage Summary</h4>
						<p class="visible-print-block">for year: <?php echo $this_year;?></p>
					</div>
          <form class="hidden-print form-inline text-center" action="/reports/drainage-ratio.php" method="post">
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
        </div>  <!-- end col6 -->
      </div>  <!-- end row -->
      <div class="row">
				<div class="col-sm-12">
          <p><br /></p>
          <?php

          //initialize variables 
			   	$all_guests = 0;
			   	$all_guides = 0;
			   	$all_swamp = 0;
			   	$all_ratio = 0;
			   	$all_guide_pay = 0;
			   	$all_swamp_pay = 0;
			   	$all_assoc_pay = 0;
			   	$all_tot_pay = 0;
			   	$count_drain = 0;

          foreach ($drainages as $drainage) {
          	$river_trips = $riverTrip->getRiverTrips($drainage['drainage'], $this_year);
          	if ($river_trips) { ?>
	 						<table class="table table-print">
								<thead>
									<tr>
										<th style="width:120px;"><?php echo $drainage['drainage']; ?></th>
										<th>Guests</th>
										<th>Guides</th>
										<th>Swampers</th>
										<th>Guide/Guest Ratio</th>
										<th>Guide Pay</th>
										<th>Swamper Pay</th>
										<th>Assoc. Pay</th>
										<th>Total Pay</th>
									</tr>
								</thead>
								<tbody>         		
		          		<?php

		          		//initialize variables
							   	$drainage_guests = 0;
							   	$drainage_guides = 0;
							   	$drainage_swamp = 0;
							   	$drainage_ratio = 0;
							   	$drainage_guide_pay = 0;
							   	$drainage_swamp_pay = 0;
							   	$drainage_assoc_pay = 0;
							   	$drainage_tot_pay = 0;
			   					
			   					$count_trips = 0;

		          		foreach ($river_trips as $river_trip) {
		          			$ratio_stats = $riverTrip->getGuideRatio($river_trip['river_trips_fk'], $this_year);
		          			$pay_stats = $riverTrip->getRiverTripPay($river_trip['river_trips_fk'], $this_year);

		        				////Print out row for each river trip
										echo '<tr>' . "\n";
										echo '<td>' . $riverTrip->getLongName($river_trip['river_trips_fk']) . '</td>' . "\n"; //River Trip Name
										echo '<td>' . $ratio_stats['tot_guests'] . '</td>' . "\n"; //User Days
										echo '<td>' . $ratio_stats['tot_guides'] . '</td>' . "\n"; //Guide Days
										echo '<td>' . $ratio_stats['tot_swamp'] . '</td>' . "\n"; //Swamper Days
										echo '<td>' . number_format((float)$ratio_stats['guest_ratio'], 2, '.', '') . '</td>' . "\n"; //Guide/Guest Ratio
										echo '<td>' . formatMoney($pay_stats['guide_tot']) . '</td>' . "\n"; //Guide Pay
										echo '<td>' . formatMoney($pay_stats['swamp_tot']) . '</td>' . "\n"; //Swamper Pay
										echo '<td>' . formatMoney($pay_stats['assoc_tot']) . '</td>' . "\n"; //Assoc Pay
										echo '<td>' . formatMoney($pay_stats['trip_total']) . '</td>' . "\n"; //Total Pay
								   	echo '</tr>' . "\n";

								   	//add to drainage total
								   	$drainage_guests += $ratio_stats['tot_guests'];
								   	$drainage_guides += $ratio_stats['tot_guides'];
								   	$drainage_swamp += $ratio_stats['tot_swamp'];
								   	$drainage_ratio += $ratio_stats['guest_ratio'];
								   	$drainage_guide_pay += $pay_stats['guide_tot'];
								   	$drainage_swamp_pay += $pay_stats['swamp_tot'];
								   	$drainage_assoc_pay += $pay_stats['assoc_tot'];
								   	$drainage_tot_pay += $pay_stats['trip_total'];

			   						$count_trips += 1;

		          		} // end for each $river_trips

	        				////Print out row for each river trip
									echo '<tr>' . "\n";
									echo '<td></td>' . "\n"; //River Trip Name
									echo '<td><strong>' . $drainage_guests . '</strong></td>' . "\n"; //User Days
									echo '<td><strong>' . $drainage_guides . '</strong></td>' . "\n"; //Guide Days
									echo '<td><strong>' . $drainage_swamp . '</strong></td>' . "\n"; //Swamper Days
									echo '<td><strong>' . number_format((float)$drainage_ratio/$count_trips, 2, '.', '') . '</strong></td>' . "\n"; //Guide/Guest Ratio
									echo '<td><strong>' . formatMoney($drainage_guide_pay) . '</strong></td>' . "\n"; //Guide Pay
									echo '<td><strong>' . formatMoney($drainage_swamp_pay) . '</strong></td>' . "\n"; //Swamper Pay
									echo '<td><strong>' . formatMoney($drainage_assoc_pay) . '</strong></td>' . "\n"; //Assoc Pay
									echo '<td><strong>' . formatMoney($drainage_tot_pay) . '</strong></td>' . "\n"; //Total Pay
							   	echo '</tr>' . "\n";

          			echo "</tbody>";
							echo "</table>";
				      echo '<div class="row hidden-print">';
								echo '<div class="col-sm-4"></div>';
								echo '<div class="col-sm-4">';
									echo '<hr class="page-line">';
								echo '</div>';
							echo '</div>';

					   	//add to all total
					   	$all_guests += $drainage_guests;
					   	$all_guides += $drainage_guides;
					   	$all_swamp += $drainage_swamp;
					   	$all_ratio += $drainage_ratio/$count_trips;
					   	$all_guide_pay += $drainage_guide_pay;
					   	$all_swamp_pay += $drainage_swamp_pay;
					   	$all_assoc_pay += $drainage_assoc_pay;
					   	$all_tot_pay += $drainage_tot_pay;

					   	$count_drain += 1;
							
          	} //end if $river_trips

          } // end for each $drainages
					?>
					<table class="table table-print">
						<thead>
							<tr>
								<th style="width:120px;"><?php echo "All Rivers"; ?></th>
								<th>Guest</th>
								<th>Guides</th>
								<th>Swampers</th>
								<th>Guide/Guest Ratio</th>
								<th>Guide Pay</th>
								<th>Swamper Pay</th>
								<th>Assoc. Pay</th>
								<th>Total Pay</th>
							</tr>
						</thead>
						<tbody>         		
	        	<?php
      				////Print out row for each river trip
							echo '<tr>' . "\n";
							echo '<td></td>' . "\n"; //River Trip Name
							echo '<td><strong>' . $all_guests . '</strong></td>' . "\n"; //User Days
							echo '<td><strong>' . $all_guides . '</strong></td>' . "\n"; //Guide Days
							echo '<td><strong>' . $all_swamp . '</strong></td>' . "\n"; //Swamper Days
							echo '<td><strong>' . number_format((float)$all_ratio/$count_drain, 2, '.', '') . '</strong></td>' . "\n"; //Guide/Guest Ratio
							echo '<td><strong>' . formatMoney($all_guide_pay) . '</strong></td>' . "\n"; //Guide Pay
							echo '<td><strong>' . formatMoney($all_swamp_pay) . '</strong></td>' . "\n"; //Swamper Pay
							echo '<td><strong>' . formatMoney($all_assoc_pay) . '</strong></td>' . "\n"; //Assoc Pay
							echo '<td><strong>' . formatMoney($all_tot_pay) . '</strong></td>' . "\n"; //Total Pay
					   	echo '</tr>' . "\n";

      			echo "</tbody>";
					echo "</table>";

          ?>
				</div> <!--end content column-->
			</div><!-- .row -->
			<?php

		include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
		?>

	</body>
</html>