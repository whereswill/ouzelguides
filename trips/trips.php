<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Trips";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
	
?>
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<h3 class="pageTitle">Trips</h3>
						<p><a href="/trips/create-trip.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Create Trip</span></a></p>
					</div>
					<div>
						<table class="table table-striped table-bordered" id="sorted_table">
							<thead>
								<tr>
									<th>Trip Name</th>
									<th style="display:none;">Sort Dates</th>
									<th>Trip Dates</th>
									<th>Assigned</th>
									<th>Approved</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 

									 $trips = $db->select("SELECT * FROM `trips` WHERE `locked_on` IS NULL");
									  foreach ($trips as $row) {
											//INSTANTIATE TRIP///// 
											$trip = new Trip();
											$trip->set_trip_id($row['trip_id']);
										
									  	echo '<tr>' . "\n";
											$name = $db->select("SELECT * FROM `river_trips` WHERE `rivertrip_id` = :river_trips_fk", array( "river_trips_fk" => $row['river_trips_fk'] ));
											$name = $name[0];
											$type = $db->select("SELECT * FROM `trip_types` WHERE `triptype_id` = :trip_types_fk", array( "trip_types_fk" => $row['trip_types_fk'] ));
											$type = $type[0];
									   	echo '<td>'. $name['rivertrip_name'] . " " . $type['triptype_name']     . "\n";
									   	echo '<td style="display:none;">' . $trip->getSortDate() . '</td>' . "\n";
									   	echo '<td>'. format_short_date($row['putin_date']) . " - " . format_date($row['takeout_date']) . '</td>' . "\n";
									   	echo '<td>'. $trip->numberOfAssigned() . '</td>' . "\n";
									   	echo '<td>';
									 		if(!is_null($row['approved_on'])) {
												echo format_date($row['approved_on']);
											}
											echo '</td>' . "\n";
									   	echo '<td width=110>' . "\n";
											echo '<div class="btn-group btn-group-xs">' . "\n";
									   	echo '<a class="btn btn-warning" href="/scheduleTrips/schedule-trip.php?trip_id='.$row['trip_id'].'"><span class="glyphicon glyphicon-plus"></span></a>' . "\n";
									   	echo '<a class="btn btn-success" href="/payTrips/pay-trip.php?trip_id='.$row['trip_id'].'"><span class="glyphicon glyphicon-usd"></span></a>' . "\n";
									   	echo '<a class="btn btn-info" href="/trips/update-trip.php?trip_id='.$row['trip_id'].'"><span class="glyphicon glyphicon-pencil"></span></a>' . "\n";
									   	echo '<a class="btn btn-danger" href="/trips/delete-trip.php?trip_id='.$row['trip_id'].'"><span class="glyphicon glyphicon-remove"></span></a>' . "\n";
											echo '</div>' . "\n";
									   	echo '</td>' . "\n";
									   	echo '</tr>' . "\n";
									 }
								?>
							</tbody>
						</table>
					</div> <!-- .row -->
				</div> <!--end content column-->
			</div> <!-- .row -->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

	</body>
</html>