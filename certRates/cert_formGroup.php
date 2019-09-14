							<div class="form-group <?php echo !empty($certrate_nameError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Cert Rate Name</label>
								<div class="col-sm-6">
									<input name="certrate_name" type="text" class="form-control" placeholder="Enter a Name" value="<?php echo !empty($certrate_name)?$certrate_name:'';?>" <?php echo !empty($locked)?'disabled':'';?>>
									<?php if (!empty($certrate_nameError)): ?>
										<span class="help-inline"><?php echo $certrate_nameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="form-group <?php echo !empty($cert_typeError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Certification Type?</label>
								<div class="col-sm-6">
									<label class="radio-inline">
									  <input type="radio" name="cert_type" value = "fa" <?php if(!empty($cert_type) && $cert_type == "fa") echo ' checked';?>> First Aid
									</label>
									<label class="radio-inline">
									  <input type="radio" name="cert_type" value = "cpr" <?php if(!empty($cert_type) && $cert_type == "cpr") echo ' checked';?>> CPR
									</label>
									<label class="radio-inline">
									  <input type="radio" name="cert_type" value = "other" <?php if(!empty($cert_type) && $cert_type == "other") echo ' checked';?>> Other
									</label>
									<?php if (!empty($cert_typeError)): ?>
										<span class="help-inline"><?php echo $cert_typeError;?></span>
									<?php endif;?>
								</div>
							</div>
							
							<div class="form-group <?php echo !empty($cert_amountError)?'has-error':'';?>">
								<label class="col-sm-3 control-label">Per day Amount</label>
								<div class="col-sm-6">
									<input type="number" name="cert_amount" class="form-control" min="0" max="9999" step="0.01" size="4" placeholder="XX.XX (no dollar sign)" value="<?php echo !empty($cert_amount)?$cert_amount:'';?>" title="no dollar sign and no comma(s)">
									<?php if (!empty($cert_amountError)): ?>
										<span class="help-inline"><?php echo $cert_amountError;?></span>
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