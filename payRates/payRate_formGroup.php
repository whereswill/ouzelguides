							<div class="form-group <?php echo !empty($payrate_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Pay Rate Name</label>
								<div class="col-sm-6">
									<input name="payrate_name" type="text" class="form-control" placeholder="Enter a Name" value="<?php echo !empty($payrate_name)?$payrate_name:'';?>">
									<?php if (!empty($payrate_nameError)): ?>
										<span class="help-inline"><?php echo $payrate_nameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($rateError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Rate</label>
								<div class="col-sm-6">
									<input type="number" name="rate" class="form-control" min="0" max="9999" step="0.01" size="4" placeholder="XX.XX (no dollar sign)" value="<?php echo !empty($rate)?$rate:'';?>" title="no dollar sign and no comma(s)" <?php //echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($rateError)): ?>
										<span class="help-inline"><?php echo $rateError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($descriptionError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Description</label>
								<div class="col-sm-6">
									<textarea name="description" class="form-control" placeholder="Optional" rows="3"><?php echo !empty($description)?$description:'';?></textarea>
									<?php if (!empty($descriptionError)): ?>
										<span class="help-inline"><?php echo $descriptionError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($active_Error)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Active?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="active" value = "N" <?php if(!empty($active) && $active == "N") echo ' checked="checked"';?>> No
									</label>
									<label class="radio-inline">
									  <input type="radio" name="active" value = "Y" <?php if(!empty($active) && $active == "Y") echo ' checked="checked"';?>> Yes
									</label>
									<?php if (!empty($activeError)): ?>
										<span class="help-inline"><?php echo $activeError;?></span>
									<?php endif;?>
								</div>
							</div>