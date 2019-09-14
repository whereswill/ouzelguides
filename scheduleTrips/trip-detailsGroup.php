<div class="col-sm-3"> <!--start split column-->
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
			<?php if ($data['turnaround'] == "Y") {
				echo "<p>Turnaround</p>";
			} else if ($data['turnaround'] == NULL) {
				echo "<p>Not Designated</p>";
			} else {
				echo "<p>Fresh Pack</p>";
			}?>
		</div>
	</div>
</div> <!--end split column-->
<div class="col-sm-3"> <!--start split column-->
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
</div> <!--end split column-->
<div class="col-sm-4"> <!--start split column-->
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
	</div><!-- 
	<div class="form-group">
		<label class="col-sm-5 control-label" style="padding-top:0px;">Trip Last Updated:</label>
		<div class="col-sm-7">
			<p>
				<?php //if (empty($data['updated_on'])) {
					//echo "Never updated";
				//} else {
					//echo format_datetime($data['updated_on']);
				//}?>
			</p>
		</div>
	</div> -->
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