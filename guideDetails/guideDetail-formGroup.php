							<div class="form-group <?php echo !empty($user_id_fkError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Guide Name</label>
								<div class="col-sm-6">
									<?php 
									$names = $db->select("SELECT `user_id`,`first_name`,`last_name` FROM `as_user_details` WHERE `user_id` NOT IN (SELECT 			`user_id_fk` FROM `guide_details`) ORDER BY `first_name` ASC");
									if (isset($user_id_fk)) {
										$currentName = $db->select("SELECT `user_id`,`first_name`,`last_name` FROM `as_user_details` WHERE `user_id` = :user_id_fk", array( "user_id_fk" => $user_id_fk ));
										array_push($names, $currentName[0]);
									}
									?>
									<select name="user_id_fk" id="select-guide-name" class="form-control" autofocus="autofocus" style="width: 100%;" <?php echo !empty($locked)?'disabled':'';?>>
										<option value="" default selected>Select a Guide</option>
										<?php foreach($names as $name) { ?>
											<option value="<?php echo $name['user_id']; ?>"<?php if(isset($user_id_fk) && $user_id_fk == $name['user_id']) echo ' selected';?>>
												<?php echo htmlentities($name['first_name'] . " " . $name['last_name']); ?>
											</option>
										<?php } ?>
									</select>
									<?php if (!empty($user_id_fkError)): ?>
										<span class="help-inline"><?php echo $user_id_fkError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($hire_dateError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Hire Date</label>
								<div class="col-sm-6">
									<input name="hire_date" type="date" class="form-control" placeholder="Hire Date" value="<?php echo !empty($hire_date)?$hire_date:'';?>">
									<?php if (!empty($hire_dateError)): ?>
										<span class="help-inline"><?php echo $hire_dateError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($bonus_startError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Bonus Start Date</label>
								<div class="col-sm-6">
									<input name="bonus_start" type="date" class="form-control" placeholder="Bonus Start Date" value="<?php echo !empty($bonus_start)?$bonus_start:'';?>">
									<?php if (!empty($bonus_startError)): ?>
										<span class="help-inline"><?php echo $bonus_startError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($bonus_eligibleError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Eligible for Bonus?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="bonus_eligible" value = "N" <?php if(!empty($bonus_eligible) && $bonus_eligible == "N") echo ' checked="checked"';?>> No
									</label>
									<label class="radio-inline">
									  <input type="radio" name="bonus_eligible" value = "Y" <?php if(!empty($bonus_eligible) && $bonus_eligible == "Y") echo ' checked="checked"';?>> Yes
									</label>
									<?php if (!empty($bonus_eligibleError)): ?>
										<span class="help-inline"><?php echo $bonus_eligibleError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($active_boolError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Active?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="active_bool" value = "N" <?php if(!empty($active_bool) && $active_bool == "N") echo ' checked="checked"';?>> No
									</label>
									<label class="radio-inline">
									  <input type="radio" name="active_bool" value = "Y" <?php if(!empty($active_bool) && $active_bool == "Y") echo ' checked="checked"';?>> Yes
									</label>
									<?php if (!empty($active_boolError)): ?>
										<span class="help-inline"><?php echo $active_boolError;?></span>
									<?php endif;?>
								</div>
							</div>