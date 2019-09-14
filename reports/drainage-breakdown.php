<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Drainage Breakdown";

	/////Instantiate objects/////////
	$river_trip = new RiverTrip();
	//$guide = new Guide;

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

	?>

<!-- PRINT SUMMARY SECTION -->

			<div class="row">
				<div class="col-sm-3 hidden-print"> <!--start sidebar column-->
				</div> <!--end sidebar column-->

				<div class="col-sm-6"> <!-- start content column -->
					<div class="text-center">
						<h4>Ouzel Outfitters YTD Pay by Drainage Breakdown</h4>
						<p class="visible-print-block">for year: <?php echo $this_year;?></p>
					</div>
          <form class="hidden-print form-inline text-center" action="/reports/drainage-breakdown.php" method="post">
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

      	<?php
    //   	$guide->set_guide_id(30);
    //   	$xx = $guide->getLockedTrips(2015);
    //   	print_r($xx);
    //   	echo "<br /><br />";
       	//$trip = new Trip();
				// $trip->set_trip_id(119);
				// $gtp = $trip->getGuideTripPay(30);
				// print_r($gtp);

				// $paid_trips = $river_trip->getYTDRiverTripPay($this_year, 3);
				// print_r($paid_trips);
    //    	echo "<br /><br />";

				// $paid_trips = $river_trip->getAllPaidSeasonRiverTrips($this_year);
				// print_r($paid_trips);
    //    	echo "<br /><br />";
				// $xx = $river_trip->getLockedRiverTrips(3, $this_year);
				// print_r($xx);
    //    	echo "<br /><br />";
	   //    $trip->set_trip_id(33);
	   //    $gtp = $trip->getTripPayBreakdown();
				// print_r($gtp);
      	?>

				<div class="col-sm-12">
          <p><br /></p>
					<table class="table table-print">
						<thead>
							<tr>
								<th style="width:120px;">Name</th>
		            <th>Guide Days</th>
		            <th>Base Pay</th>
		            <th>TL Pay</th>
		            <th>Sat Pay</th>
		            <th>Bump Pay</th>
		            <th>Rig Pay</th>
		            <th>Shop Pay</th>
		            <th>Other Pay</th>
		            <th>FA/SRT Certs</th>
		            <th>Guide Total</th>
		            <th>Avg/Guide Day</th>
		            <th>Emp. Bonus</th>
		            <th>Assoc Pay</th>
		            <th>Total w/ Bonus</th>
							</tr>
						</thead>
						<tbody>         		
          		<?php
          		$r_trips = $river_trip->getAllPaidSeasonRiverTrips($this_year);

		          //initialize variables 

          		$drainage = $r_trips[0]['drainage'];

					   	$ytd_river_days_tot = 0;
					   	$base_pay_tot = 0;
					   	$tl_pay_tot = 0;
					   	$sat_pay_tot = 0;
					   	$bump_pay_tot = 0;
					   	$rig_pay_tot = 0;
					   	$shop_pay_tot = 0;
					   	$other_pay_tot = 0;
					   	$cert_pay_tot = 0;
					   	$ytd_guide_total_tot = 0;
					   	$bonus_pay_tot = 0;
					   	$assoc_pay_tot = 0;

          		foreach ($r_trips as $rt) {

          			if ($drainage <> $rt['drainage']) {
		      				//Print out drainage total row
									echo '<tr>' . "\n";
									echo '<td><strong>' . $drainage . '</strong></td>' . "\n"; //Guide Name
									echo '<td><strong>' . $ytd_river_days_drain . '</strong></td>' . "\n"; //drainal Guide Days
									echo '<td><strong>' . formatMoney($base_pay_drain) . '</strong></td>' . "\n"; //drainal Base Pay
									echo '<td><strong>' . formatMoney($tl_pay_drain) . '</strong></td>' . "\n"; //drainal TL Pay
									echo '<td><strong>' . formatMoney($sat_pay_drain) . '</strong></td>' . "\n"; //drainal Sat Pay
									echo '<td><strong>' . formatMoney($bump_pay_drain) . '</strong></td>' . "\n"; //drainal Bump Pay
									echo '<td><strong>' . formatMoney($rig_pay_drain) . '</strong></td>' . "\n"; //drainal Rig Pay
									echo '<td><strong>' . formatMoney($shop_pay_drain) . '</strong></td>' . "\n"; //drainal Shop Pay
									echo '<td><strong>' . formatMoney($other_pay_drain) . '</strong></td>' . "\n"; //drainal Other Pay
									echo '<td><strong>' . formatMoney($cert_pay_drain) . '</strong></td>' . "\n"; //drainal FA/SRT Certs
									echo '<td><strong>' . formatMoney($ytd_guide_total_drain) . '</strong></td>' . "\n"; //drainal Guide drainal
									echo '<td><strong>' . formatMoney($ytd_guide_total_drain/$ytd_river_days_drain) . '</strong></td>' . "\n"; //drainal Avg/River Day
									echo '<td><strong>' . formatMoney($bonus_pay_drain) . '</strong></td>' . "\n"; //drainal Emp. Bonus
									echo '<td><strong>' . formatMoney($assoc_pay_drain) . '</strong></td>' . "\n"; //drainal Assoc Pay
									echo '<td><strong>' . formatMoney($ytd_guide_total_drain+$assoc_pay_drain+$bonus_pay_drain) . '</strong></td>' . "\n"; //drainal w/ Bonus
							   	echo '</tr>' . "\n";
					   			echo '<tr><td colspan="15"></td></tr>' . "\n";

							   	//reset drainage totals
							   	$ytd_river_days_drain = 0;
							   	$base_pay_drain = 0;
							   	$tl_pay_drain = 0;
							   	$sat_pay_drain = 0;
							   	$bump_pay_drain = 0;
							   	$rig_pay_drain = 0;
							   	$shop_pay_drain = 0;
							   	$other_pay_drain = 0;
							   	$cert_pay_drain = 0;
							   	$ytd_guide_total_drain = 0;
							   	$bonus_pay_drain = 0;
							   	$assoc_pay_drain = 0;

          			}

          			$drainage = $rt['drainage'];

	          		$trip_stats = $river_trip->getYTDRiverTripPay($this_year, $rt['river_trips_fk']);

        				//Print out row for each river trip
								echo '<tr>' . "\n";
								//echo '<td>' . $river_trip->getLongName($rt['river_trips_fk']) . '</td>' . "\n"; //Guide Name
								echo '<td>' . $rt['name'] . '</td>' . "\n"; //Guide Name
								echo '<td>' . $trip_stats['ytd_guide_days'] . '</td>' . "\n"; //Guide Days
								echo '<td>' . formatMoney($trip_stats['base_pay']) . '</td>' . "\n"; //Base Pay
								echo '<td>' . formatMoney($trip_stats['tl_pay']) . '</td>' . "\n"; //TL Pay
								echo '<td>' . formatMoney($trip_stats['sat_pay']) . '</td>' . "\n"; //Sat Pay
								echo '<td>' . formatMoney($trip_stats['bump_pay']) . '</td>' . "\n"; //Bump Pay
								echo '<td>' . formatMoney($trip_stats['rig_pay']) . '</td>' . "\n"; //Rig Pay
								echo '<td>' . formatMoney($trip_stats['shop_pay']) . '</td>' . "\n"; //Shop Pay
								echo '<td>' . formatMoney($trip_stats['other_pay']) . '</td>' . "\n"; //Other Pay
								echo '<td>' . formatMoney($trip_stats['cert_pay']) . '</td>' . "\n"; //FA/SRT Certs
								echo '<td>' . formatMoney($trip_stats['ytd_guide_total']) . '</td>' . "\n"; //Guide Total
								echo '<td>' . formatMoney($trip_stats['ytd_guide_total']/$trip_stats['ytd_guide_days']) . '</td>' . "\n"; //Avg/River Day
								echo '<td>' . formatMoney($trip_stats['bonus_pay']) . '</td>' . "\n"; //Emp. Bonus
								echo '<td>' . formatMoney($trip_stats['assoc_pay']) . '</td>' . "\n"; //Assoc Pay
								echo '<td>' . formatMoney($trip_stats['ytd_guide_total']+$trip_stats['bonus_pay']+$trip_stats['assoc_pay']) . '</td>' . "\n"; //Total w/ Bonus
						   	echo '</tr>' . "\n";

				 		   	//add to river trip total
						   	$ytd_river_days_tot += $trip_stats['ytd_guide_days'];
						   	$base_pay_tot += $trip_stats['base_pay'];
						   	$tl_pay_tot += $trip_stats['tl_pay'];
						   	$sat_pay_tot += $trip_stats['sat_pay'];
						   	$bump_pay_tot += $trip_stats['bump_pay'];
						   	$rig_pay_tot += $trip_stats['rig_pay'];
						   	$shop_pay_tot += $trip_stats['shop_pay'];
						   	$other_pay_tot += $trip_stats['other_pay'];
						   	$cert_pay_tot += $trip_stats['cert_pay'];
						   	$ytd_guide_total_tot += $trip_stats['ytd_guide_total'];
						   	$bonus_pay_tot += $trip_stats['bonus_pay'];
						   	$assoc_pay_tot += $trip_stats['assoc_pay'];

				 		   	//add to drainage total
						   	$ytd_river_days_drain += $trip_stats['ytd_guide_days'];
						   	$base_pay_drain += $trip_stats['base_pay'];
						   	$tl_pay_drain += $trip_stats['tl_pay'];
						   	$sat_pay_drain += $trip_stats['sat_pay'];
						   	$bump_pay_drain += $trip_stats['bump_pay'];
						   	$rig_pay_drain += $trip_stats['rig_pay'];
						   	$shop_pay_drain += $trip_stats['shop_pay'];
						   	$other_pay_drain += $trip_stats['other_pay'];
						   	$cert_pay_drain += $trip_stats['cert_pay'];
						   	$ytd_guide_total_drain += $trip_stats['ytd_guide_total'];
						   	$bonus_pay_drain += $trip_stats['bonus_pay'];
						   	$assoc_pay_drain += $trip_stats['assoc_pay'];				  

           		} // end for each $river_trips

      				//Print out last drainage total row
							echo '<tr>' . "\n";
							echo '<td><strong>' . $drainage . '</strong></td>' . "\n"; //Guide Name
							echo '<td><strong>' . $ytd_river_days_drain . '</strong></td>' . "\n"; //drainal Guide Days
							echo '<td><strong>' . formatMoney($base_pay_drain) . '</strong></td>' . "\n"; //drainal Base Pay
							echo '<td><strong>' . formatMoney($tl_pay_drain) . '</strong></td>' . "\n"; //drainal TL Pay
							echo '<td><strong>' . formatMoney($sat_pay_drain) . '</strong></td>' . "\n"; //drainal Sat Pay
							echo '<td><strong>' . formatMoney($bump_pay_drain) . '</strong></td>' . "\n"; //drainal Bump Pay
							echo '<td><strong>' . formatMoney($rig_pay_drain) . '</strong></td>' . "\n"; //drainal Rig Pay
							echo '<td><strong>' . formatMoney($shop_pay_drain) . '</strong></td>' . "\n"; //drainal Shop Pay
							echo '<td><strong>' . formatMoney($other_pay_drain) . '</strong></td>' . "\n"; //drainal Other Pay
							echo '<td><strong>' . formatMoney($cert_pay_drain) . '</strong></td>' . "\n"; //drainal FA/SRT Certs
							echo '<td><strong>' . formatMoney($ytd_guide_total_drain) . '</strong></td>' . "\n"; //drainal Guide drainal
							echo '<td><strong>' . formatMoney($ytd_guide_total_drain/$ytd_river_days_drain) . '</strong></td>' . "\n"; //drainal Avg/River Day
							echo '<td><strong>' . formatMoney($bonus_pay_drain) . '</strong></td>' . "\n"; //drainal Emp. Bonus
							echo '<td><strong>' . formatMoney($assoc_pay_drain) . '</strong></td>' . "\n"; //drainal Assoc Pay
							echo '<td><strong>' . formatMoney($ytd_guide_total_drain+$assoc_pay_drain+$bonus_pay_drain) . '</strong></td>' . "\n"; //drainal w/ Bonus
					   	echo '</tr>' . "\n";
					   	echo '<tr><td colspan="15"></td></tr>' . "\n";

      				//Print out total row
							echo '<tr>' . "\n";
							echo '<td></td>' . "\n"; //Guide Name
							echo '<td><strong>' . $ytd_river_days_tot . '</strong></td>' . "\n"; //Total Guide Days
							echo '<td><strong>' . formatMoney($base_pay_tot) . '</strong></td>' . "\n"; //Total Base Pay
							echo '<td><strong>' . formatMoney($tl_pay_tot) . '</strong></td>' . "\n"; //Total TL Pay
							echo '<td><strong>' . formatMoney($sat_pay_tot) . '</strong></td>' . "\n"; //Total Sat Pay
							echo '<td><strong>' . formatMoney($bump_pay_tot) . '</strong></td>' . "\n"; //Total Bump Pay
							echo '<td><strong>' . formatMoney($rig_pay_tot) . '</strong></td>' . "\n"; //Total Rig Pay
							echo '<td><strong>' . formatMoney($shop_pay_tot) . '</strong></td>' . "\n"; //Total Shop Pay
							echo '<td><strong>' . formatMoney($other_pay_tot) . '</strong></td>' . "\n"; //Total Other Pay
							echo '<td><strong>' . formatMoney($cert_pay_tot) . '</strong></td>' . "\n"; //Total FA/SRT Certs
							echo '<td><strong>' . formatMoney($ytd_guide_total_tot) . '</strong></td>' . "\n"; //Total Guide Total
							echo '<td><strong>' . formatMoney($ytd_guide_total_tot/$ytd_river_days_tot) . '</strong></td>' . "\n"; //Total Avg/River Day
							echo '<td><strong>' . formatMoney($bonus_pay_tot) . '</strong></td>' . "\n"; //Total Emp. Bonus
							echo '<td><strong>' . formatMoney($assoc_pay_tot) . '</strong></td>' . "\n"; //Total Assoc Pay
							echo '<td><strong>' . formatMoney($ytd_guide_total_tot+$assoc_pay_tot+$bonus_pay_tot) . '</strong></td>' . "\n"; //Total w/ Bonus
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