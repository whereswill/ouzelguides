<!-- USER PHONE NUMBER -->

<div class="modal" id="modal-add-phone" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modal-phone">
          Add Phone Number
        </h4>
      </div>
      <div class="modal-body" id="phone-body">
        <form class="form-horizontal" id="add-phone-form">
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="select-phone-type">
              Type
            </label>
            <div class="col-sm-6">
              <select name="select-phone-type" id="select-phone-type" class="form-control" style="width: 100%;">
                <option value="" default selected>Select a Type</option>
                <option value="Mobile">Mobile</option>
                <option value="Home">Home</option>
                <option value="Parent">Parent</option>
                <option value="Partner">Partner</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="phone_number">
              Phone Number
            </label>
            <div class="col-sm-6">
              <input id="phone_number" name="phone_number" type="text" class="input-xlarge form-control">
            </div>
          </div>
          <div class="control-group form-group">
            <label class="control-label col-lg-3" for="phone-order">
              Call Order
            </label>
            <div class="col-sm-6">
              <input id="phone-order" name="phone-order" type="number" class="input-xlarge form-control" >
            </div>
          </div>
        </form>
      </div>
      <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
      <div class="modal-footer">
        <a href="javascript:void(0);" id="btn-cancel-phone" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
          Cancel
        </a>
        <a href="javascript:void(0);" id="btn-add-phone" class="btn btn-primary">
          <span>Add</span>
        </a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- USER ADDRESS -->

<div class="modal" id="modal-add-address" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modal-address">
          Add Address
        </h4>
      </div>
      <div class="modal-body" id="address-body">
        <form class="form-horizontal" id="add-address-form">
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="select-address-type">
              Address Type
            </label>
            <div class="col-sm-6">
              <select name="select-address-type" id="select-address-type" class="form-control" style="width: 100%;">
                <option value="" default selected>Select a Type</option>
                <option value="Home">Home</option>
                <option value="Parent">Parent</option>
                <option value="Winter">Winter</option>
                <option value="W-2 Only">W-2 Only</option>
                <option value="Summer">Summer</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          <div class="control-group form-group">
            <label class="control-label col-lg-3" for="address-co">
              Care of
            </label>
            <div class="col-sm-6">
              <input id="address-co" name="address-co" type="text" class="input-xlarge form-control" >
            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="address-street1">
              Street 1
            </label>
            <div class="col-sm-6">
              <input id="address-street1" name="address-street1" type="text" class="input-xlarge form-control">
            </div>
          </div>
          <div class="control-group form-group">
            <label class="control-label col-lg-3" for="address-street2">
              Street 2
            </label>
            <div class="col-sm-6">
              <input id="address-street2" name="address-street2" type="text" class="input-xlarge form-control" >
            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="address-city">
              City
            </label>
            <div class="col-sm-6">
              <input id="address-city" name="address-city" type="text" class="input-xlarge form-control" >
            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="address-state">
              State
            </label>
            <div class="col-sm-6">
              <input id="address-state" name="address-state" type="text" class="input-xlarge form-control" >
            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="address-zip">
              Zip Code
            </label>
            <div class="col-sm-6">
              <input id="address-zip" name="address-zip" type="text" class="input-xlarge form-control" >
            </div>
          </div>
        </form>
      </div>
      <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
      <div class="modal-footer">
        <a href="javascript:void(0);" id="btn-cancel-address" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
          Cancel
        </a>
        <a href="javascript:void(0);" id="btn-add-address" class="btn btn-primary">
          <span>Add</span>
        </a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- EMERGENCY CONTACT -->

<div class="modal" id="modal-add-ec" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modal-ec">
          Add Emergency Contact
        </h4>
      </div>
      <div class="modal-body" id="ec-body">
        <form class="form-horizontal" id="add-ec-form">
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="select-ec-relation">
              Relation
            </label>
            <div class="col-sm-6">
              <select name="select-ec-relation" id="select-ec-relation" class="form-control" style="width: 100%;">
                <option value="" default selected>Select a Relation</option>
                <option value="Spouse">Spouse</option>
                <option value="Parent">Parent</option>
                <option value="Sibling">Sibling</option>
                <option value="Friend">Friend</option>
                <option value="Partner">Partner</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="ec-name">
              Name
            </label>
            <div class="col-sm-6">
              <input id="ec-name" name="ec-name" type="text" class="input-xlarge form-control" >
            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="ec-phone1">
              Primary Phone
            </label>
            <div class="col-sm-6">
              <input id="ec-phone1" name="ec-phone1" type="text" class="input-xlarge form-control">
            </div>
          </div>
          <div class="control-group form-group">
            <label class="control-label col-lg-3" for="ec-phone2">
              Secondary Phone
            </label>
            <div class="col-sm-6">
              <input id="ec-phone2" name="ec-phone2" type="text" class="input-xlarge form-control" >
            </div>
          </div>
          <div class="control-group form-group">
            <label class="control-label col-lg-3" for="ec-email">
              Email
            </label>
            <div class="col-sm-6">
              <input id="ec-email" name="ec-email" type="email" class="input-xlarge form-control" >
            </div>
          </div>
        </form>
      </div>
      <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
      <div class="modal-footer">
        <a href="javascript:void(0);" id="btn-cancel-ec" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
          Cancel
        </a>
        <a href="javascript:void(0);" id="btn-add-ec" class="btn btn-primary">
          <span>Add</span>
        </a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--ADD PAYRATE-->

<div class="modal" id="modal-add-payrate" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modal-cert">
          Add Pay Rate
        </h4>
      </div>
      <div class="modal-body" id="cert-body">
        <form class="form-horizontal" id="add-payrate-form">
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="select-payrate">
              Pay Rate
            </label>
            <div class="col-sm-6">
              <?php
              $pay_rates = $db->select("SELECT `payrate_id`,`rate` FROM `pay_rates`");
              ?>
              <select name="payrate_id" id="select-payrate" class="form-control" style="width: 100%;">
                <option value="" default selected>Select a Pay Rate</option>
                <?php foreach($pay_rates as $rate) { ?>
                  <option value="<?php echo $rate['payrate_id']; ?>">
                    <?php echo htmlentities($rate['rate']); ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="control-group form-group">
            <label class="control-label col-lg-3" for="addpayrate-notes">
              Notes
            </label>
            <div class="controls col-lg-6">
              <input id="addpayrate-notes" name="addpayrate-notes" type="text" class="input-xlarge form-control" placeholder="Notes">
            </div>
          </div>
        </form>
      </div>
      <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
      <div class="modal-footer">
        <a href="javascript:void(0);" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
          Cancel
        </a>
        <a href="javascript:void(0);" id="btn-add-payrate" class="btn btn-primary">
          <span>Add</span>
        </a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--ADD CERTIFICATION-->

<div class="modal" id="modal-add-cert" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modal-cert">
          Add Certification
        </h4>
      </div>
      <div class="modal-body" id="cert-body">
        <form class="form-horizontal" id="add-cert-form">
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="select-cert-name">
              Certification
            </label>
            <div class="col-sm-6">
              <?php
              $certs = $db->select("SELECT `certrate_id`,`certrate_name` FROM `cert_rates`");
              ?>
              <select name="certrate_id" id="select-cert-name" class="form-control" style="width: 100%;">
                <option value="" default selected>Select a Certification</option>
                <?php foreach($certs as $cert) { ?>
                  <option value="<?php echo $cert['certrate_id']; ?>">
                    <?php echo htmlentities($cert['certrate_name']); ?>
                  </option>
                <?php } ?>
              </select>

            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="addcert-expdate">
              Expiration Date
            </label>
            <div class="controls col-lg-6">
              <input id="addcert-expdate" name="addcert-expdate" type="date" class="input-xlarge form-control" >
            </div>
          </div>
        </form>
      </div>
      <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
      <div class="modal-footer">
        <a href="javascript:void(0);" id="btn-cancel-cert" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
          Cancel
        </a>
        <a href="javascript:void(0);" id="btn-add-cert" class="btn btn-primary">
          <span>Add</span>
        </a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--ADD NOTE-->

<div class="modal" id="modal-add-note" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modal-note">
          Add Note
        </h4>
      </div>
      <div class="modal-body" id="note-body">
        <form class="form-horizontal" id="add-note-form">
          <div class="control-group form-group required">
            <div class="col-sm-1">
            </div>
            <div class="col-sm-10">
              <textarea class="form-control" id="note-text"></textarea>
              <input type="checkbox" name="is_public" id="public-box"> OK for guide see this comment?<br>
            </div>
          </div>
        </form>
      </div>
      <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
      <div class="modal-footer">
        <a href="javascript:void(0);" id="btn-cancel-note" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
          Cancel
        </a>
        <a href="javascript:void(0);" id="btn-add-note" class="btn btn-primary">
          <span>Add</span>
        </a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--CHANGE PASSWORD-->

<div class="modal" id="modal-change-pass" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modal-pass">
          Change Password
        </h4>
      </div>
      <div class="modal-body" id="pass-body">
        <form class="form-horizontal" id="change-pass-form">
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="new-pass">
              New Password
            </label>
            <div class="col-sm-6">
              <input id="new-pass" name="new-pass" type="password" class="input-xlarge form-control" >
            </div>
          </div>
          <div class="control-group form-group required">
            <label class="control-label col-lg-3" for="pass-repeat">
              Expiration Date
            </label>
            <div class="controls col-lg-6">
              <input id="pass-repeat" name="pass-repeat" type="password" class="input-xlarge form-control" >
            </div>
          </div>
        </form>
      </div>
      <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
      <div class="modal-footer">
        <a href="javascript:void(0);" id="btn-cancel-pass" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
          Cancel
        </a>
        <a href="javascript:void(0);" id="btn-change-pass" class="btn btn-primary">
          <span>Change</span>
        </a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

