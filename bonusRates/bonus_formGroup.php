							<div class="form-group <?php echo !empty($bonusrate_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Bonus Rate Name</label>
								<div class="col-sm-6">
									<input name="bonusrate_name" type="text" class="form-control" placeholder="Enter a Name" value="<?php echo !empty($bonusrate_name)?$bonusrate_name:'';?>">
									<?php if (!empty($bonusrate_nameError)): ?>
										<span class="help-inline"><?php echo $bonusrate_nameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($num_yearsError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Years of Service</label>
								<div class="col-sm-6">
									<input type="number" name="num_years" class="form-control" min="0" max="100" step="1" size="3" placeholder="select 1 thru 20" value="<?php echo !empty($num_years)?$num_years:'';?>" title="whole number and no comma(s)">
									<?php if (!empty($num_yearsError)): ?>
										<span class="help-inline"><?php echo $num_yearsError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($bonus_amountError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Per day Amount</label>
								<div class="col-sm-6">
									<input type="number" name="bonus_amount" class="form-control" min="0" max="9999" step="0.01" size="4" placeholder="XX.XX (no dollar sign)" value="<?php echo !empty($bonus_amount)?$bonus_amount:'';?>" title="no dollar sign and no comma(s)">
									<?php if (!empty($bonus_amountError)): ?>
										<span class="help-inline"><?php echo $bonus_amountError;?></span>
									<?php endif;?>
								</div>
							</div>