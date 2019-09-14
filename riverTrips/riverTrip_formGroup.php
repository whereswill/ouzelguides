							<div class="form-group <?php echo !empty($rivertrip_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Short Trip Name</label>
								<div class="col-sm-6">
									<input name="rivertrip_name" type="text" class="form-control" placeholder="Short Trip Name" value="<?php echo !empty($rivertrip_name)?$rivertrip_name:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($rivertrip_nameError)): ?>
										<span class="help-inline"><?php echo $rivertrip_nameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($longnameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Long Name</label>
								<div class="col-sm-6">
									<input name="longname" type="text" class="form-control" placeholder="Long Name" value="<?php echo !empty($longname)?$longname:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($longnameError)): ?>
										<span class="help-inline"><?php echo $longnameError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($drainageError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Drainage</label>
								<div class="col-sm-6">
									<select name="drainage" id="select-drainage" class="form-control" style="width: 100%;" <?php echo !empty($locked)?'disabled':'';?>>
										<option value="" default selected>Select a Drainage</option>
										<option value="Deschutes" <?php if(isset($drainage) && $drainage == "Deschutes") echo ' selected';?>>Deschutes</option>
										<option value="McKenzie" <?php if(isset($drainage) && $drainage == "McKenzie") echo ' selected';?>>McKenzie</option>
										<option value="Rogue" <?php if(isset($drainage) && $drainage == "Rogue") echo ' selected';?>>Rogue</option>
										<option value="Salmon" <?php if(isset($drainage) && $drainage == "Salmon") echo ' selected';?>>Salmon</option>
										<option value="Umpqua" <?php if(isset($drainage) && $drainage == 'Umpqua') echo ' selected';?>>Umpqua</option>
										<option value="John Day" <?php if(isset($drainage) && $drainage == "John Day") echo ' selected';?>>John Day</option>
										<option value="Owyhee" <?php if(isset($drainage) && $drainage == "Owyhee") echo ' selected';?>>Owyhee</option>
										<option value="Santiam" <?php if(isset($drainage) && $drainage == "Santiam") echo ' selected';?>>Santiam</option>
										<option value="Klamath" <?php if(isset($drainage) && $drainage == "Klamath") echo ' selected';?>>Klamath</option>
										<option value="Other" <?php if(isset($drainage) && $drainage == "Other") echo ' selected';?>>Other</option>
									</select>
									<?php if (!empty($drainageError)): ?>
										<span class="help-inline"><?php echo $drainageError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($putin_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Put-in Name</label>
								<div class="col-sm-6">
									<input name="putin_name" type="text" class="form-control" placeholder="Put-in Name" value="<?php echo !empty($putin_name)?$putin_name:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($putin_nameError)): ?>
										<span class="help-inline"><?php echo $putin_nameError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($takeout_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Take-out Name</label>
								<div class="col-sm-6">
									<input name="takeout_name" type="text" class="form-control" placeholder="Take-out Name" value="<?php echo !empty($takeout_name)?$takeout_name:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($takeout_nameError)): ?>
										<span class="help-inline"><?php echo $takeout_nameError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($satelliteError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Satellite or Local?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="satellite" value = "N" <?php if(!empty($satellite) && $satellite == "N") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Local
									</label>
									<label class="radio-inline">
									  <input type="radio" name="satellite" value = "Y" <?php if(!empty($satellite) && $satellite == "Y") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Satellite
									</label>
									<?php if (!empty($satelliteError)): ?>
										<span class="help-inline"><?php echo $satelliteError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($mileageError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Mileage</label>
								<div class="col-sm-6">
									<input name="mileage" type="text" class="form-control" placeholder="Mileage" value="<?php echo !empty($mileage)?$mileage:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($mileageError)): ?>
										<span class="help-inline"><?php echo $mileageError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($descriptionError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Description</label>
								<div class="col-sm-6">
									<textarea name="description" class="form-control" placeholder="Description" rows="3" <?php echo !empty($locked)?'disabled':'';?>><?php echo !empty($description)?$description:'';?></textarea>
									<?php if (!empty($descriptionError)): ?>
										<span class="help-inline"><?php echo $descriptionError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($dd_orderError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Drop-down Order</label>
								<div class="col-sm-6">
									<input name="dd_order" type="text" class="form-control" placeholder="Drop-down Order" value="<?php echo !empty($dd_order)?$dd_order:'';?>">
									<?php if (!empty($dd_orderError)): ?>
										<span class="help-inline"><?php echo $dd_orderError;?></span>
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