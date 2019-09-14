<div class="col-sm-3"> <!--start split column-->
	<div class="form-group">
		<label class="col-sm-5 control-label" style="padding-top:0px;">User Name:</label>
		<div class="col-sm-7">
			<p><?php echo $user->getUserName();?></p>
		</div>
	</div>
</div> <!--end split column-->
<div class="col-sm-3"> <!--start split column-->
</div> <!--end split column-->
<div class="col-sm-4"> <!--start split column-->
	<div class="form-group">
		<label class="col-sm-5 control-label" style="padding-top:0px;">Created:</label>
		<div class="col-sm-7">
			<p><?php echo format_datetime($data['created_on']);?></p>
		</div>
	</div>
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