<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Phone List";

	/////Instantiate objects/////////
	$guide = new Guide;
	// $trip = new Trip;

	// $this_year = $date->format("Y");
	// if (isset($_POST['years'])) {
	//   if (empty($_POST['years'])) {
	//     $yearsError = 'Please choose a year';
	//     $valid = false;
	//   }
	//   $this_year = $_POST['years'];
	// }

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

	///Get phones for all active or all guides
	$phones = $guide->getActiveGuides();
	?>

<!-- PRINT SUMMARY SECTION -->

			<div class="row">
				<div class="col-sm-3 hidden-print"> <!--start sidebar column-->
				</div> <!--end sidebar column-->

				<div class="col-sm-6"> <!-- start content column -->
					<div class="text-center">
						<h4>Ouzel Outfitters Active Guides Phone List</h4>
					</div>
					<table class="table table-striped table-print">
						<thead>
							<tr>
								<th>Seniority</th>
								<th>Guide</th>
								<th>Phones</th>
							</tr>
						</thead>
						<tbody>
							<?php 		

							foreach ($phones as $phone) {

								$guide->set_guide_id($phone['user_id']);
								$numbers = $guide->getUserPhones();

								////Print out total row for each guide
								echo '<tr>' . "\n";
								echo '<td>' . $guide->getSeniority() . '</td>' . "\n"; //Seniority
								echo '<td>' . $guide->getUserName() . '</td>' . "\n"; //Name
								echo '<td>';
								if($numbers) {
									foreach ($numbers as $number) {
										echo $number['phone_number'] . "<br />";
									}
								}
								echo '</td>' . "\n"; //Phone Numbers
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