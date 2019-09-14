							<div class="form-group <?php echo !empty($role_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Role Name</label>
								<div class="col-sm-6">
									<input name="role_name" type="text" class="form-control" placeholder="Enter a Name" value="<?php echo !empty($role_name)?$role_name:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($role_nameError)): ?>
										<span class="help-inline"><?php echo $role_nameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($role_typeError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Role Type</label>
								<div class="col-sm-6">
									<select name="role_type" id="select-roleType" class="form-control" style="width: 100%;" <?php echo !empty($locked)?'disabled':'';?>>
											<option value="" default selected>Select a Role Type</option>
											<option value = 1 <?php if(isset($role_type) && $role_type == 1) echo ' selected';?>>Guide</option>
											<option value = 2 <?php if(isset($role_type) && $role_type == 2) echo ' selected';?>>Other</option>
									</select>
									<?php if (!empty($role_typeError)): ?>
										<span class="help-inline"><?php echo $role_typeError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($rateError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Default Rate</label>
								<div class="col-sm-6">
									<input type="number" name="rate" class="form-control" min="0" max="9999" step="0.01" size="4" placeholder="XX.XX (no dollar sign)" value="<?php echo !empty($rate)?$rate:'';?>" title="no dollar sign and no comma(s)" <?php echo isset($role_id) && !isRoleEditable($role_id)?'disabled':'';?>>
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
							<div class="form-group <?php echo !empty($dd_orderError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Drop-down Order</label>
								<div class="col-sm-6">
									<input name="dd_order" type="text" class="form-control" placeholder="Position of selection in drop-down" value="<?php echo !empty($dd_order)?$dd_order:'';?>">
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