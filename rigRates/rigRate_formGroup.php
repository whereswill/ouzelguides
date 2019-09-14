							<div class="form-group">
								<label class="col-sm-3 control-label" style="padding-top:0px;">Instructions:</label>
								<div class="col-sm-6">
									<p>Use the following criteria to define the Rig Rate amount. For a trip to get Rig Pay, it must meet all of the following criteria. If it doesn't meet the criteria, that pay will not be applied. If no Rig Pay meet the criteria of a trip, none will be paid whether riggers are designated or not. If more than one type of Rig Pay meet the criteria, the first will be used. Rig Pay will be split evenly among all riggers.</p>
								</div>
							</div>
							<div class="form-group <?php echo !empty($rigrate_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">TL Rate Name</label>
								<div class="col-sm-6">
									<input name="rigrate_name" type="text" class="form-control" placeholder="Enter a Name" value="<?php echo !empty($rigrate_name)?$rigrate_name:'';?>">
									<?php if (!empty($rigrate_nameError)): ?>
										<span class="help-inline"><?php echo $rigrate_nameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($satellite_boolError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Satellite or Local?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="satellite_bool" value = "N" <?php if(!empty($satellite_bool) && $satellite_bool == "N") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Local
									</label>
									<label class="radio-inline">
									  <input type="radio" name="satellite_bool" value = "Y" <?php if(!empty($satellite_bool) && $satellite_bool == "Y") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Satellite
									</label>
									<?php if (!empty($satellite_boolError)): ?>
										<span class="help-inline"><?php echo $satellite_boolError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($turnaround_boolError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Fresh Pack or Turnaround?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="turnaround_bool" value = "N" <?php if(!empty($turnaround_bool) && $turnaround_bool == "N") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Fresh Pack
									</label>
									<label class="radio-inline">
									  <input type="radio" name="turnaround_bool" value = "Y" <?php if(!empty($turnaround_bool) && $turnaround_bool == "Y") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Turnaround
									</label>
									<?php if (!empty($turnaround_boolError)): ?>
										<span class="help-inline"><?php echo $turnaround_boolError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($rig_amountError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Total Amount</label>
								<div class="col-sm-6">
									<input type="number" name="rig_amount" class="form-control" min="0" max="9999" step="0.01" size="4" placeholder="XX.XX (no dollar sign)" value="<?php echo !empty($rig_amount)?$rig_amount:'';?>" title="no dollar sign and no comma(s)">
									<?php if (!empty($rig_amountError)): ?>
										<span class="help-inline"><?php echo $rig_amountError;?></span>
									<?php endif;?>
								</div>
							</div>