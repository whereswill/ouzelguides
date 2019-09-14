							<div class="form-group <?php echo !empty($triptype_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Trip Type Name</label>
								<div class="col-sm-6">
									<input name="triptype_name" type="text" class="form-control" placeholder="Enter a Name" value="<?php echo !empty($triptype_name)?$triptype_name:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($triptype_nameError)): ?>
										<span class="help-inline"><?php echo $triptype_nameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($descriptionError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Description</label>
								<div class="col-sm-6">
									<textarea name="description" class="form-control" placeholder="Enter a Description" rows="3"><?php echo !empty($description)?$description:'';?></textarea>
									<?php if (!empty($descriptionError)): ?>
										<span class="help-inline"><?php echo $descriptionError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($dd_orderError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Drop-down Order</label>
								<div class="col-sm-6">
									<input name="dd_order" type="text" class="form-control" placeholder="Position in drop-down selection" value="<?php echo !empty($dd_order)?$dd_order:'';?>">
									<?php if (!empty($dd_orderError)): ?>
										<span class="help-inline"><?php echo $dd_orderError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($active_Error)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Active?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="active" value = "N" <?php if(!empty($active) && $active == "N") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> No
									</label>
									<label class="radio-inline">
									  <input type="radio" name="active" value = "Y" <?php if(!empty($active) && $active == "Y") echo ' checked="checked"';?> <?php echo !empty($locked)?'disabled':'';?>> Yes
									</label>
									<?php if (!empty($activeError)): ?>
										<span class="help-inline"><?php echo $activeError;?></span>
									<?php endif;?>
								</div>
							</div>