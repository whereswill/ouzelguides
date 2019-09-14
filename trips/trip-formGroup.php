							<div class="form-group <?php echo !empty($river_trips_fkError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Trip Name</label>
								<div class="col-sm-6">
									<?php $names = $db->select("SELECT `rivertrip_id`, `rivertrip_name` FROM `river_trips` WHERE `active` = 'Y' ORDER BY `rivertrip_name` ASC"); ?>
									<select name="river_trips_fk" id="select-trip-name" class="form-control" autofocus="autofocus" style="width: 100%;">
										<option value="" default selected>Select a River Trip</option>
										<?php foreach($names as $name) { ?>
											<option value="<?php echo $name['rivertrip_id']; ?>"<?php if(isset($river_trips_fk) && $river_trips_fk == $name['rivertrip_id']) echo ' selected';?>>
												<?php echo htmlentities($name['rivertrip_name']); ?>
											</option>
										<?php } ?>
									</select>
									<?php if (!empty($river_trips_fkError)): ?>
										<span class="help-inline"><?php echo $river_trips_fkError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($trip_types_fkError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Trip Type</label>
								<div class="col-sm-6">
									<?php $types = $db->select("SELECT * FROM `trip_types` WHERE `active` = 'Y' ORDER BY `dd_order` ASC"); ?>
									<select name="trip_types_fk" id="select-trip-type" class="form-control" style="width: 100%;">
										<option value="" default selected>Select a Trip Type</option>
										<?php foreach($types as $type) { ?>
											<option value="<?php echo $type['triptype_id']; ?>"<?php if(isset($trip_types_fk) && $trip_types_fk == $type['triptype_id']) echo ' selected';?>>
												<?php echo htmlentities($type['triptype_name']); ?>
											</option>
										<?php } ?>
									</select>
									<?php if (!empty($trip_types_fkError)): ?>
										<span class="help-inline"><?php echo $trip_types_fkError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($putin_dateError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Put-in Date</label>
								<div class="col-sm-6">
									<input name="putin_date" id="piDate" type="date" class="form-control" placeholder="Put-in Date" value="<?php echo !empty($putin_date)?$putin_date:'';?>">
									<?php if (!empty($putin_dateError)): ?>
										<span class="help-inline"><?php echo $putin_dateError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="form-group <?php echo !empty($takeout_dateError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Take-out Date</label>
								<div class="col-sm-6">
									<input name="takeout_date" id="toDate" type="date" class="form-control" placeholder="Take_out Date" value="<?php echo !empty($takeout_date)?$takeout_date:'';?>">
									<?php if (!empty($takeout_dateError)): ?>
										<span class="help-inline"><?php echo $takeout_dateError;?></span>
									<?php endif;?>
								</div>
							</div>

							<div class="form-group <?php echo !empty($guests_numError)?'has-error':'';?>">
								<label class="col-sm-3 control-label"># of Guests</label>
								<div class="col-sm-6">
									<input name="guests_num" type="number" step="1" class="form-control" placeholder="# of Guests" value="<?php echo !empty($guests_num)?$guests_num:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($guests_numError)): ?>
										<span class="help-inline"><?php echo $guests_numError;?></span>
									<?php endif;?>
								</div>
							</div>

							<div class="form-group <?php echo !empty($turnaroundError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Rig Type?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input id="radio-fresh" type="radio" name="turnaround" value = "N" <?php if(isset($turnaround) && $turnaround == "N") echo ' checked="checked"';?>> Fresh Pack
									</label>
									<label class="radio-inline">
									  <input id="radio-ta" type="radio" name="turnaround" value = "Y" <?php if(isset($turnaround) && $turnaround == "Y") echo ' checked="checked"';?>> Turnaround
									</label>
									<?php if (!empty($turnaroundError)): ?>
										<span class="help-inline"><?php echo $turnaroundError;?></span>
									<?php endif;?>
								</div>
							</div>