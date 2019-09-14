							<div class="form-group">
								<label class="col-sm-3 control-label" style="padding-top:0px;">Instructions:</label>
								<div class="col-sm-6">
									<p>Use the following criteria to define the TL Rate amount. For a guide to get TL Pay, the trip must meet all of the following criteria. If no TL Pay meet the criteria of a trip, none will be paid whether a TL is designated or not. TL Pay will be paid to each TL.</p>
								</div>
							</div>
							<div class="form-group <?php echo !empty($tlrate_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">TL Rate Name</label>
								<div class="col-sm-6">
									<input name="tlrate_name" type="text" class="form-control" placeholder="Enter a Name" value="<?php echo !empty($tlrate_name)?$tlrate_name:'';?>">
									<?php if (!empty($tlrate_nameError)): ?>
										<span class="help-inline"><?php echo $tlrate_nameError;?></span>
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
							<div class="form-group <?php echo !empty($day_boolError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Multi-day or Day-trip?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="day_bool" value = "N" <?php if(!empty($day_bool) && $day_bool == "N") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Multi-day
									</label>
									<label class="radio-inline">
									  <input type="radio" name="day_bool" value = "Y" <?php if(!empty($day_bool) && $day_bool == "Y") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Day-trip
									</label>
									<?php if (!empty($day_boolError)): ?>
										<span class="help-inline"><?php echo $day_boolError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($tl_amountError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Per day Amount</label>
								<div class="col-sm-6">
									<input type="number" name="tl_amount" class="form-control" min="0" max="9999" step="0.01" size="4" placeholder="XX.XX (no dollar sign)" value="<?php echo !empty($tl_amount)?$tl_amount:'';?>" title="no dollar sign and no comma(s)">
									<?php if (!empty($tl_amountError)): ?>
										<span class="help-inline"><?php echo $tl_amountError;?></span>
									<?php endif;?>
								</div>
							</div>