<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "River Days";

	/////Instantiate objects/////////
	$guide = new Guide;

	$this_date = new DateTime();
	if (isset($_POST['start_date'])) {
	  //set start date to date and end date to end of that year
	  $this_year = strtotime($_POST['start_date']);
	  $this_year = date("Y", $this_year);
		//$this_year = $_POST['years']->format("Y");
		$this_date->setDate($this_year, 12, 31);
		$end_date = $this_date->format('Y-m-d');
		$start_date = $_POST['start_date'];
	} else {
	  //set start date to first and end date to last of this year
		$this_year = $this_date->format("Y");
		$this_date->setDate($this_year, 12, 31);
		$end_date = $this_date->format('Y-m-d');
		$this_date->setDate($this_year, 1, 1);
		$start_date = $this_date->format('Y-m-d');
	}

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

	///Get list of users on approved trips and timesheets
	$all_guides = $guide->getGuidesByRiverDays($start_date, $end_date);
	?>

<!-- PRINT SUMMARY SECTION -->

			<div class="row">
				<div class="col-sm-2 hidden-print"> <!--start sidebar column-->
				</div> <!--end sidebar column-->

				<div class="col-sm-8"> <!-- start content column -->
					<div class="text-center">
						<h4>Ouzel Outfitters Current River Days Summary</h4>
						<p class="visible-print-block">Starting at: <?php echo format_date($start_date);?></p>
					</div>
          <form class="hidden-print form-inline text-center" action="/reports/river-days.php" method="post">
						<div class="form-group <?php echo !empty($start_dateError)?'has-error':'';?>">
							<label for="start_date">Starting at:</label>
							<input name="start_date" id="select-date" type="date" class="form-control input-sm" style="width: 150px;" placeholder="Hire Date" value="<?php echo !empty($start_date)?$start_date:'';?>">
							<?php if (!empty($start_dateError)): ?>
								<span class="help-inline"><?php echo $start_dateError;?></span>
							<?php endif;?>
						</div>
            <button type="submit" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Go</span></button>
          </form>
					<table class="table table-striped table-print" id="sorted_table_5">
						<thead>
							<tr>
								<th>Seniority</th>
								<th>Name</th>
								<th>Best Phone</th>
								<th>Base Rate</th>
								<th>River Days</th>
								<th>YTD Pay</th>
							</tr>
						</thead>
						<tbody>
							<?php 		
							$total_river_days = 0;

							foreach ($all_guides as $rd_guide) {

								$guide->set_guide_id($rd_guide['user_id_fk']);

								$river_days = $rd_guide['river_days'];

								////Print out total row for each guide
								echo '<tr>' . "\n";
								echo '<td><a href="/users/update-user.php?user_id_fk='.$rd_guide['user_id_fk'].'">' . $guide->getSeniority() . '</a></td>' . "\n"; //Seniority
								echo '<td>' . $guide->getUserName() . '</td>' . "\n"; //Name
								echo '<td>' . $guide->getBestPhone() . '</td>' . "\n"; //Best Phone
								echo '<td>' . $guide->getCurrentPayrate() . '</td>' . "\n"; //Base Rate
								echo '<td>' . $river_days . '</td>' . "\n"; //River Days
								$ytdPay = $guide->getYTDTripPay($this_year); //YTD Pay
								echo '<td>' . formatMoney($ytdPay['ytd_guide_total']) . '</td>' . "\n"; //YTD Pay
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