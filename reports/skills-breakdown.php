<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Guide Breakdown";

	/////Instantiate objects/////////
	$guide = new Guide;

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

	?>

<!-- PRINT SUMMARY SECTION -->

			<div class="row hidden-print">
				<div class="col-sm-3 hidden-print"> <!--start sidebar column-->
				</div> <!--end sidebar column-->

				<div class="col-sm-6"> <!-- start content column -->
					<div class="text-center">
						<h4>Ouzel Outfitters YTD Skills by Guide Breakdown</h4>
					</div>
        </div>  <!-- end col6 -->
      </div>  <!-- end row -->
      <div class="row">

      	<?php
      	$riverTrips = new RiverTrip();
      	$river_trips = $riverTrips->getTrips();
      	$allSkills = new Skill();
      	$skills = $allSkills->getSkills();
      	?>

				<div class="col-sm-12">
					<table class="table table-center table-print">
						<thead>
							<tr>
								<th class="name-cell">Name</th>
								<th class="table-spacer"></th>
								<?php
								$skill_count = 0;
								$skillArray = [];
								$trip_count = 0;
								$tripArray = [];
								foreach ($skills as $gs) {
									echo '<th class="rotate"><div><span>' . $gs['skill_name'] . '</span></div></th>';
									$skill_count++;
									$skillArray[$skill_count] = $gs['skill_id'];
								}
								echo '<th class="table-spacer"></th>';
								foreach ($river_trips as $rt) {
									echo '<th class="rotate"><div><span>' . $rt['rivertrip_name'] . '</span></div></th>';
									$trip_count++;
									$tripArray[$trip_count] = $rt['rivertrip_id'];
								}
								?>
							</tr>
						</thead>
						<tbody>         		
          		<?php

          		$active_guides = $guide->getActiveGuides();

          		foreach ($active_guides as $g) {

          			$guide->set_guide_id($g['user_id']);

	          		$guide_skills = $guide->getGuideSkillsID();
	          		$guide_rivers = $guide->getGuideRiversID();

									echo '<tr>' . "\n";
										echo '<td class="name-cell">' . $guide->getUserName() . '</td>' . "\n"; //Guide Name
										echo '<td class="table-spacer"></td>';

										for ($i=1; $i <= $skill_count ; $i++) { 
											if ($guide_skills && in_array($skillArray[$i], $guide_skills)) {
											echo '<td><span class="glyphicon glyphicon-ok text-success"></span></td>' . "\n"; //Guide Skill
											} else {
											echo '<td></td>' . "\n"; //No Guide Skill
											}
										}
										echo '<td class="table-spacer"></td>';

										for ($i=1; $i <= $trip_count ; $i++) { 
											if ($guide_rivers && in_array($tripArray[$i], $guide_rivers)) {
											echo '<td><span class="glyphicon glyphicon-ok text-success"></span></td>' . "\n"; //Guide River
											} else {
											echo '<td></td>' . "\n"; //No Guide River
											}
										}
							   	echo '</tr>' . "\n";

           		} // end for each $guides

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