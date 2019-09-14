<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
		exit();
   }

	//variable to identify this page title
	$title = "Trips";

	if ( !empty($_POST)) {

		// keep track validation errors
		$river_trips_fkError = null;
		$trip_types_fkError = null;
		$putin_dateError = null;
		$takeout_dateError = null;
		$guests_numError = null;
		$turnaroundError = null;
		
		// keep track post values
		$river_trips_fk = $_POST['river_trips_fk'];
		$trip_types_fk = $_POST['trip_types_fk'];
		$putin_date = $_POST['putin_date'];
		$takeout_date = $_POST['takeout_date'];
		$guests_num = $_POST['guests_num'];
		$turnaround = $_POST['turnaround'];
		
		// validate input
		$valid = true;
		if (empty($river_trips_fk)) {
			$river_trips_fkError = 'Please enter a trip name';
			$valid = false;
		}
		
		if (empty($trip_types_fk)) {
			$trip_types_fkError = 'Please enter a trip type';
			$valid = false;
		} else if ($trip_types_fk == 11) { // is it a daily double, determine if there is a half day scheduled  && !empty($putin_date)
				$trip = new Trip;
				if (!$trip->isAnotherHalfDay($putin_date, $river_trips_fk)) {
					$trip_types_fkError = 'There is not another 1/2D scheduled on this day and river. Please schedule the first 1/2D before proceeding';
					$valid = false;
				}
		}
		
		if (empty($putin_date)) {
			$putin_dateError = 'Please enter a put-in date';
			$valid = false;
		}

		if (empty($takeout_date)) {
			$takeout_dateError = 'Please enter a take-out date';
			$valid = false;
		} else if (new DateTime($putin_date) > new DateTime($takeout_date)) {
			$takeout_dateError = 'Please choose a date that is later than the put-in date';
			$valid = false;
		}
		
		if (empty($guests_num)) {
			$guests_numError = 'Please enter the number of guests';
			$valid = false;
		}
		
		if (empty($turnaround)) {
			$turnaroundError = 'Please select whether the trip is a Turnaround or Fresh Pack';
			$valid = false;
		}
		
		// insert data
		if ($valid) {		
			$trip_array = array(
		    'river_trips_fk' => $river_trips_fk,
		    'trip_types_fk' => $trip_types_fk,
		    'putin_date' => $putin_date,
		    'takeout_date' => $takeout_date,
		    'guests_num' => $guests_num,
		    'turnaround' => $turnaround,
		    'created_on' => $datetime,
		    'created_by' => $visitor_id,
			);
			$q = $db->insert('trips',$trip_array);
			
			if(!empty($_REQUEST['schedule'])) {
				$last_id = $db->lastInsertId();
				header("Location: /scheduleTrips/schedule-trip.php?trip_id=" . $last_id);
				exit();
			} else {
				header("Location: /trips/trips.php");
				exit();
			}
		}
	}
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>

			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->	
					<form class="form-horizontal" action="/trips/create-trip.php" method="post">
						<fieldset id="no-margin">
							<legend>Create a Trip</legend>
							<?php
								include 'trip-formGroup.php';
							?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<button type="submit" name="schedule" value="schedule"  class="btn btn-low btn-success">Create & Schedule</button>
									<button type="submit" name="create" value="create" class="btn btn-low btn-success">Create</button>
									<a class="btn btn-low btn-primary" href="<?php echo $previous?>">Back</a>
								</div>
							</div>
						</fieldset>
					</form>
				</div> <!--end content column-->
			</div><!--row-->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>
<script type="text/javascript">
	$(document).ready(function() {
		var now = new Date();
		var lastWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 10);
		var day = ("0" + lastWeek.getDate()).slice(-2);
 		var month = ("0" + (lastWeek.getMonth() + 1)).slice(-2);
 		var weekago = lastWeek.getFullYear()+"-"+(month)+"-"+(day);
 		$('#piDate').val(weekago);
 		$('#toDate').val(weekago);

 		$('#piDate').blur(function() { // Change the rig radio button to Fresh for 1 and 1/2 days
    	var selected_trip = $("#select-trip-name option:selected").text();
    	
    	var n = jQuery.trim(selected_trip);
    	n = selected_trip.lastIndexOf("D");
      if (n != -1) {
      	var num_days = selected_trip.charAt(n-1);
      	if (selected_trip.charAt(n-2) == "/") {
      		num_days = 1;
      	}
      	var pi = new Date($('#piDate').val());
      	var to = new Date(pi.getFullYear(), pi.getMonth(), pi.getDate() + +num_days);
				var day = ("0" + to.getDate()).slice(-2);
 				var month = ("0" + (to.getMonth() + 1)).slice(-2);
 				var to_date = to.getFullYear()+"-"+(month)+"-"+(day);
    		$('#toDate').val(to_date);
      }
    }); // end change

 		$('#select-trip-type').change(function() { // Change the rig radio button to Fresh for 1 and 1/2 days
    	var selected_id = $(this).val();
      if (selected_id == 5 || selected_id == 9) {
    		$('#radio-fresh').prop('checked', true);
      	$('#radio-ta').prop('checked', false);
      } else {
      	$('#radio-fresh').prop('checked', false);
      	$('#radio-ta').prop('checked', false);
      }
    }); // end change
	}); // end ready
</script>

<script type="text/javascript">
	$('input#piDate').on("blur", function() {
		var x = document.getElementById("piDate").value;
 		$('#toDate').val(x);
	});
</script>


	</body>
</html>