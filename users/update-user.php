<?php 
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

//SET PERMISSIONS FOR PAGE
if(!$visitor->isAdmin() && $_REQUEST['user_id_fk'] != $visitor_id) {
  header("Location: /users/update-user.php?user_id_fk=$visitor_id");
  exit();
}

//variable to identify this page title
$title = "Users";

//get user id and instantiate object. If no id then exit page
if (!empty($_GET['user_id_fk'])) {
    $user_id_fk = $_REQUEST['user_id_fk'];

    //INSTANTIATE USER CLASS///////////
    $user = new User();
    $user->set_user_id($user_id_fk);
    $guide = new Guide();
    $guide->set_guide_id($user_id_fk);
    $userNotes = new Note();
    $rivertrips = new RiverTrip();

    //Get details
    $userDetails = $user->getAll();
    $userAddress = $user->getUserAddresses();
    $userPhone = $user->getUserPhones();
    $userEC = $user->getUserECs();
    $guideDetails = $guide->getGuideDetails();
    $pay_rate = $guide->getGuidePayrates();
    $cert = $guide->getGuideCerts();
    $guideSkills = $guide->getGuideSkills();
    $riverAwareness = $guide->getGuideRivers();
    $notes = $userNotes->getUserNotes($user_id_fk, $visitor->isAdmin());

} else {
    header("Location: /users.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';


  if ($visitor->isAdmin()) { ?>
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="col-sm-9">
          <h3>User Details: <?php echo $user->getUserName(); ?></h3>
          <!-- <p>Awareness Details: <?php //print_r($riverAwareness); ?></p> -->
        </div>
        <div class="col-sm-3">
          <a class="btn btn-low btn-primary pull-right" href="<?php echo $previous?>">Back</a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
  <?php } else { ?>
    <div class="row">
      <div class="col-sm-3 bs-docs-sidebar">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/side-nav.php'; ?>
      </div>
      <div class="col-sm-9">
        <h3 id="comments-title">My Profile</h3>
  <?php } ?>

            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab8default" data-toggle="tab">Profile</a></li>
                        <li><a href="#tab1default" data-toggle="tab">User Details</a></li>
                        <li><a href="#tab2default" data-toggle="tab">Phone Numbers</a></li>
                        <li><a href="#tab3default" data-toggle="tab">Address</a></li>
                        <li><a href="#tab4default" data-toggle="tab">Emergency Contact</a></li>
                        <li><a href="#tab9default" data-toggle="tab">Medical</a></li>
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown">Guide Info<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#tab5default" data-toggle="tab">Details</a></li>
                                <li><a href="#tab6default" data-toggle="tab">Pay Rates</a></li>
                                <li><a href="#tab7default" data-toggle="tab">Certifications</a></li>
                                <li><a href="#tab11default" data-toggle="tab">Guide Skills</a></li>
                                <li><a href="#tab12default" data-toggle="tab">River Awareness</a></li>
                            </ul>
                        </li>
                        <li><a href="#tab10default" data-toggle="tab">Notes</a></li>
                    </ul>
                </div>

                <!-- Set visitor and user IDs -->
                <input type="hidden" id="form-visitorId" value="<?php echo $visitor_id; ?>" />
                <input type="hidden" id="form-userId" value="<?php echo $user_id_fk; ?>" />

                <div class="panel-body">
                    <div class="tab-content">

                        <div class="tab-pane fade in form-horizontal active" id="tab8default">
                          <div class="col-sm-10" id="user_profile">
                            <fieldset id="no-margin">
                              
                              <input type="hidden" id="userProfile-userId" value="<?php echo $user_id_fk; ?>" />

                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="username">
                                  User Name
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userProfile-username" name="username" type="text" <?php echo !$visitor->isAdmin() ? 'disabled="disabled"' : "" ; ?> value="<?php echo htmlentities($userDetails['username']); ?>" class="input-xlarge form-control">
                                </div>
                              </div>
                              
                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="email">
                                  Email Address
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userProfile-email" name="email" type="text" <?php echo !$visitor->isAdmin() ? 'disabled="disabled"' : "" ; ?> value="<?php echo htmlentities($userDetails['email']); ?>" class="input-xlarge form-control">
                                </div>
                              </div>
                              
                              <!-- Text input-->
                              <div class="form-group info-group">
                                <label class="col-sm-4 control-label" for="last_login">
                                  Last Login
                                </label>
                                <div class="col-sm-7 control-group">
                                  <?php if ($userDetails['last_login'] == '0000-00-00 00:00:00') {
                                    echo '<p>Never logged in</p>';
                                  } else {
                                    echo '<p>' . htmlentities(format_datetime($userDetails['last_login'])) . '</p>';
                                  } ?>
                                </div>
                              </div>
                            </fieldset>
                          </div> <!-- end col-sm-10 -->
                          <?php if($visitor->isAdmin()) { ?>
                            <button class="col-sm-2 btn-group-sm btn btn-info" id="btn-update-profile">Update Profile</button>
                            <br/><br/>
                            <button class="col-sm-2 btn-group-sm btn btn-warning" id="btn-update-password" data-toggle="modal" data-target="#modal-change-pass">Change Password</button>
                          <?php } ?>
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade in form-horizontal" id="tab1default">
                          <div class="col-sm-10" id="user_details">
                            <fieldset id="no-margin">
                              
                              <input type="hidden" id="userDetail-userId" value="<?php echo $user_id_fk; ?>" />

                              <?php if($visitor->isAdmin()) { ?>
                              <!-- Checkbox input-->
                              <div class="form-group check-group">
                                <label class="col-sm-4 control-label" for="active">
                                  Active?
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userDetail_active" name="active" type="checkbox" value="Y" <?php echo $userDetails['active'] == "Y" ? 'checked="checked"' : "" ; ?>>
                                </div>
                              </div>
                              <?php } ?>

                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="first_name">
                                  First Name
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userDetail_first_name" name="first_name" type="text" value="<?php echo htmlentities($userDetails['first_name']); ?>" class="input-xlarge form-control">
                                </div>
                              </div>
                              
                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="middle_name">
                                  Middle Name
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userDetail_middle_name" name="middle_name" type="text" value="<?php echo htmlentities($userDetails['middle_name']); ?>" class="input-xlarge form-control">
                                </div>
                              </div>
                              
                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="last_name">
                                  Last Name
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userDetail_last_name" name="last_name" type="text" value="<?php echo htmlentities($userDetails['last_name']); ?>" class="input-xlarge form-control">
                                </div>
                              </div>
                              
                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="nickname">
                                  Nickname
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userDetail_nickname" name="nickname" type="text" value="<?php echo htmlentities($userDetails['nickname']); ?>" class="input-xlarge form-control">
                                </div>
                              </div>
                              
                              <!-- Date input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="birthdate">
                                  Birth Date
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userDetail_birthdate" name="birthdate" type="date" value="<?php echo htmlentities($userDetails['birthdate']); ?>" class="input-xlarge form-control">
                                </div>
                              </div>
                              
                             <!-- Select input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="t_size">
                                  T-shirt Size
                                </label>
                                <div class="col-sm-7 control-group">
                                  <select name="t_size" id="userDetail_t_size" class="input-xlarge form-control" style="width: 100%;">
                                    <option value="" default selected>Select a Size</option>
                                    <option value="XS"<?php if(isset($userDetails['t_size']) && $userDetails['t_size'] == 'XS') echo ' selected';?>>XSmall</option>
                                    <option value="S"<?php if(isset($userDetails['t_size']) && $userDetails['t_size'] == 'S') echo ' selected';?>>Small</option>
                                    <option value="M"<?php if(isset($userDetails['t_size']) && $userDetails['t_size'] == 'M') echo ' selected';?>>Medium</option>
                                    <option value="L"<?php if(isset($userDetails['t_size']) && $userDetails['t_size'] == 'L') echo ' selected';?>>Large</option>
                                    <option value="XL"<?php if(isset($userDetails['t_size']) && $userDetails['t_size'] == 'XL') echo ' selected';?>>XLarge</option>
                                    <option value="XXL"<?php if(isset($userDetails['t_size']) && $userDetails['t_size'] == 'XXL') echo ' selected';?>>XXLarge</option>
                                  </select>
                                </div>
                              </div>
                              
                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="star_sign">
                                  Star Sign
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="userDetail_star_sign" name="star_sign" type="text" value="<?php echo htmlentities($userDetails['star_sign']); ?>" class="input-xlarge form-control">
                                </div>
                              </div>
                            </fieldset>
                          </div> <!-- end col-sm-10 -->
                          <button class="col-sm-2 btn-group-sm btn btn-info" id="btn-update-user">Update Details</button>
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade" id="tab2default">
                          <div class="col-sm-10" id="userPhone">
                            <table class="table table-striped" id="phone-list">
                              <thead>
                                <tr>
                                  <th>Type</th>
                                  <th>Phone Number</th>
                                  <th>Best Order</th>
                                  <th>Remove</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                if(isset($userPhone) && !$userPhone == false) { 
                                  foreach($userPhone as $phone) {
                                    //Print out phone numbers
                                    echo '<tr>' . "\n";
                                    echo '<td>' . $phone['phone_type'] . '</td>' . "\n"; //Type
                                    echo '<td>' . $phone['phone_number'] . '</td>' . "\n"; //Number
                                    echo '<td>' . $phone['best_order'] . '</td>' . "\n"; //Best order
                                    echo '<td class="remove">';
                                      echo '<a href="javascript:void(0);" onclick="deletePhone(this,' . $phone['phone_id'] . ');">';
                                        echo '<i class="icon-trash glyphicon glyphicon-trash"></i>';
                                      echo '</a>';
                                    echo '</td>' . "\n"; //Remove
                                    echo '</tr>' . "\n";
                                  }
                                } else {
                                  //Print out empty table
                                  echo '<tr>' . "\n";
                                  echo '<td id="first_cell">No Phone Data</td>' . "\n"; //Type
                                  echo '<td></td>' . "\n"; //Number
                                  echo '<td></td>' . "\n"; //Best Order
                                  echo '<td></td>' . "\n"; //Remove
                                  echo '</tr>' . "\n";
                                } ?>
                              </tbody>
                            </table>
                          </div> <!-- end col-sm-10 -->
                          <button class="col-sm-2 btn-group-sm btn btn-warning" id="add-phone" data-toggle="modal" data-target="#modal-add-phone">Add Phone Number</button>
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade" id="tab3default">
                          <div class="col-sm-10" id="userAddress">
                            <table class="table table-striped" id="address-list">
                              <thead>
                                <tr>
                                  <th>Type</th>
                                  <th>Address</th>
                                  <th>City</th>
                                  <th>State</th>
                                  <th>Zip</th>
                                  <th>Remove</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                if(isset($userAddress) && !$userAddress == false) { 
                                  foreach($userAddress as $address) {
                                    ////Print out addresses
                                    echo '<tr>' . "\n";
                                    echo '<td>' . $address['address_type'] . '</td>' . "\n"; //Type
                                    echo '<td>';
                                    if ($address['care_of']) {
                                      echo 'c/o: ' . htmlentities($address['care_of']) . '<br />';
                                    }
                                    echo $address['street_one']; //Address
                                    if ($address['street_two']) {
                                      echo '<br />' . htmlentities($address['street_two']);
                                    }
                                    echo '</td>' . "\n"; //Address
                                    echo '<td>' . $address['city'] . '</td>' . "\n"; //City
                                    echo '<td>' . $address['state'] . '</td>' . "\n"; //State
                                    echo '<td>' . $address['postal_code'] . '</td>' . "\n"; //Zip
                                    echo '<td class="remove">';
                                      echo '<a href="javascript:void(0);" onclick="deleteAddress(this,' . $address['address_id'] . ');">';
                                        echo '<i class="icon-trash glyphicon glyphicon-trash"></i>';
                                      echo '</a>';
                                    echo '</td>' . "\n"; //Remove
                                    echo '</tr>' . "\n";
                                  }
                                } else {
                                  //Print out empty table
                                  echo '<tr>' . "\n";
                                  echo '<td id="first_cell">No Address Data</td>' . "\n"; //Type
                                  echo '<td></td>' . "\n"; //Adress
                                  echo '<td></td>' . "\n"; //City
                                  echo '<td></td>' . "\n"; //State
                                  echo '<td></td>' . "\n"; //Zip
                                  echo '<td></td>' . "\n"; //Remove
                                  echo '</tr>' . "\n";
                                } ?>
                              </tbody>
                            </table>
                          </div> <!-- end col-sm-10 -->
                          <button class="col-sm-2 btn-group-sm btn btn-warning" id="add_address" data-toggle="modal" data-target="#modal-add-address">Add Address</button>
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade" id="tab4default">
                          <div class="col-sm-10">
                            <table class="table table-striped" id="ec-list">
                              <thead>
                                <tr>
                                  <th>Relation</th>
                                  <th>Name</th>
                                  <th>Phone 1</th>
                                  <th>Phone 2</th>
                                  <th>Email</th>
                                  <th>Remove</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                if(isset($userEC) && !$userEC == false) {
                                  foreach($userEC as $ec) {
                                    ////Print out emergency contacts
                                    echo '<tr>' . "\n";
                                    echo '<td>' . $ec['ec_relation'] . '</td>' . "\n"; //Relation
                                    echo '<td>' . $ec['ec_name'] . '</td>' . "\n"; //Name
                                    echo '<td>' . $ec['ec_phone'] . '</td>' . "\n"; //Phone 1
                                    echo '<td>' . $ec['ec_phone2'] . '</td>' . "\n"; //Phone 2
                                    echo '<td>' . $ec['ec_email'] . '</td>' . "\n"; //Email
                                    echo '<td class="remove">';
                                      echo '<a href="javascript:void(0);" onclick="deleteEC(this,' . $ec['ec_id'] . ');">';
                                        echo '<i class="icon-trash glyphicon glyphicon-trash"></i>';
                                      echo '</a>';
                                    echo '</td>' . "\n"; //Remove
                                    echo '</tr>' . "\n";
                                  }
                                } else {
                                  ////Print out empty table
                                  echo '<tr>' . "\n";
                                  echo '<td id="first_cell">No Emergency Contacts</td>' . "\n"; //Relation
                                  echo '<td></td>' . "\n"; //Name
                                  echo '<td></td>' . "\n"; //Phone 1
                                  echo '<td></td>' . "\n"; //Phone 2
                                  echo '<td></td>' . "\n"; //Email
                                  echo '<td></td>' . "\n"; //Remove
                                  echo '</tr>' . "\n";
                                } ?>
                              </tbody>
                            </table>
                          </div> <!-- end col-sm-10 -->
                          <button class="col-sm-2 btn-group-sm btn btn-warning" id="add_ec_contact" data-toggle="modal" data-target="#modal-add-ec">Add Contact</button>
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade in form-horizontal" id="tab9default">
                          <div class="col-sm-10" id="userMed">
                            <fieldset id="no-margin user-medGroup">

                              <!-- Text input-->
                              <div class="form-group control-group">
                                <input type="hidden" id="userMed-userId" value="<?php echo $user_id_fk; ?>" />
                                <label class="col-sm-4 control-label" for="medical">
                                  Medical Concerns
                                </label>
                                <div class="col-sm-7 control-group">
                                  <textarea id="userMed-medical" name="medical" class="input-xlarge form-control"><?php echo htmlentities($userDetails['medical']); ?></textarea>
                                </div>
                              </div>
                              
                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="allergy">
                                  Allergies
                                </label>
                                <div class="col-sm-7 control-group">
                                  <textarea id="userMed-allergy" name="allergy" type="text" class="input-xlarge form-control"><?php echo htmlentities($userDetails['allergy']); ?></textarea>
                                </div>
                              </div>
                              
                              <!-- Text input-->
                              <div class="form-group">
                                <label class="col-sm-4 control-label" for="dietary">
                                  Dietary Restrictions
                                </label>
                                <div class="col-sm-7 control-group">
                                  <textarea id="userMed-dietary" name="dietary" type="text" class="input-xlarge form-control"><?php echo htmlentities($userDetails['dietary']); ?></textarea>
                                </div>
                              </div>
                            </fieldset>
                          </div> <!-- end col-sm-10 -->
                          <button class="col-sm-2 btn-group-sm btn btn-info" id="btn-update-medical">Update Weaknesses</button>
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade in form-horizontal" id="tab5default">
                          <div class="col-sm-10" id="guide_details">
                            <fieldset id="no-margin guide-detailsGroup">
                              <!-- Checkbox input-->
                              <div class="form-group control-group check-group info-group">
                                <input type="hidden" id="guideDetail-userId" value="<?php echo $user_id_fk; ?>" />
                                <label class="col-sm-4 control-label" for="active_bool">
                                  Active Guide?
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input class="input-xlarge input-guideDetail" id="guideDetail_active_bool" name="active_bool" type="checkbox" <?php echo !$visitor->isAdmin() ? 'disabled="disabled"' : "" ; ?><?php echo $guideDetails['active_bool'] == "Y" ? 'checked="checked"' : "" ; ?>>
                                </div>
                              </div>

                              <!-- Number input-->
                              <div class="form-group control-group">
                                <label class="col-sm-4 control-label" for="seniority">
                                  Seniority
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="guideDetail_seniority" name="seniority" type="number" min="1" max="50" step="1" value="<?php echo htmlentities($guideDetails['seniority']); ?>" <?php echo !$visitor->isAdmin() ? 'disabled="disabled"' : "" ; ?> class="input-xlarge form-control">
                                </div>
                              </div>
                            
                              <!-- Date input-->
                              <div class="form-group control-group required">
                                <label class="col-sm-4 control-label" for="hire_date">
                                  Hire Date
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="guideDetail_hire_date" name="hire_date" type="date" value="<?php echo htmlentities($guideDetails['hire_date']); ?>" <?php echo !$visitor->isAdmin() ? 'disabled="disabled"' : "" ; ?> class="input-xlarge form-control">
                                </div>
                              </div>

                              <!-- Checkbox input-->
                              <div class="form-group control-group check-group">
                                <label class="col-sm-4 control-label" for="bonus_eligible">
                                  Bonus Eligible?
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="guideDetail_bonus_eligible" name="bonus_eligible" type="checkbox" <?php echo !$visitor->isAdmin() ? 'disabled="disabled"' : "" ; ?> <?php echo $guideDetails['bonus_eligible'] == "Y" ? 'checked="checked"' : "" ; ?>>
                                </div>
                              </div>
                            
                              <!-- Date input-->
                              <div class="form-group control-group">
                                <label class="col-sm-4 control-label" for="bonus_start">
                                  Bonus Start Date
                                </label>
                                <div class="col-sm-7 control-group">
                                  <input id="guideDetail_bonus_start" name="bonus_start" type="date" value="<?php echo htmlentities($guideDetails['bonus_start']); ?>" <?php echo !$visitor->isAdmin() ? 'disabled="disabled"' : "" ; ?> class="input-xlarge form-control">
                                </div>
                              </div>
                            </fieldset>
                          </div> <!-- end col-sm-10 -->
                          <?php if($visitor->isAdmin()) { ?>
                            <?php if ($guideDetails){
                              echo '<button class="col-sm-2 btn-group-sm btn btn-info" id="btn-update-guide">Update Guide</button>';
                            } else {
                              echo '<button class="col-sm-2 btn-group-sm btn btn-warning" id="btn-add-guide">Add as Guide</button>';
                            } ?>
                          <?php } ?>
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade" id="tab6default">
                          <div class="col-sm-10">
                            <table class="table table-striped" id="payrate-list">
                              <thead>
                                <tr>
                                  <th>Rate</th>
                                  <th>Start Date</th>
                                  <th>Notes</th>
                                  <?php if($visitor->isAdmin()) { ?>
                                    <th>Remove</th>
                                  <?php } ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                if(isset($pay_rate) && !$pay_rate == false) {
                                  foreach($pay_rate as $pay_rate) {
                                    //Print out pay rates
                                    echo '<tr>' . "\n";
                                    echo '<td>' . $pay_rate['rate'] . '</td>' . "\n"; //Rate
                                    echo '<td>' . format_date($pay_rate['created_on']) . '</td>' . "\n"; //Start
                                    echo '<td>' . $pay_rate['notes'] . '</td>' . "\n"; //Notes
                                    if($visitor->isAdmin()) { 
                                      echo '<td class="remove">';
                                        echo '<a href="javascript:void(0);" onclick="deletePayRate(this,' . $pay_rate['guidepayrate_id'] . ');">';
                                          echo '<i class="icon-trash glyphicon glyphicon-trash"></i>';
                                        echo '</a>';
                                      echo '</td>' . "\n"; //Remove
                                    }
                                    echo '</tr>' . "\n";
                                  }
                                } else {
                                  ////Print out empty table
                                  echo '<tr>' . "\n";
                                  echo '<td id="first_cell">No Pay Rates</td>' . "\n"; //Rate
                                  echo '<td></td>' . "\n"; //Start
                                  echo '<td></td>' . "\n"; //Notes
                                  echo '<td></td>' . "\n"; //Remove
                                  echo '</tr>' . "\n";
                                } ?>
                              </tbody>
                            </table>
                          </div> <!-- end col-sm-10 -->
                          <?php if($visitor->isAdmin()) { ?>
                            <button class="col-sm-2 btn-group-sm btn btn-warning" id="add_pay_rate" data-toggle="modal" data-target="#modal-add-payrate">Add Pay Rate</button>
                          <?php } ?>
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade" id="tab7default">
                          <div class="col-sm-10"> 
                            <table class="table table-striped" id="cert-list">
                              <thead>
                                <tr>
                                  <th>Certification</th>
                                  <th>Expires</th>
                                  <th>Current</th>
                                  <?php if($visitor->isAdmin()) { ?>
                                    <th>Remove</th>
                                  <?php } ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                if(isset($cert) && !$cert == false) {
                                  foreach($cert as $cert) {
                                    ////Print out certs
                                    echo '<tr>' . "\n";
                                    echo '<td>' . $cert['certrate_name'] . '</td>' . "\n"; //Name
                                    echo '<td>' . format_date($cert['exp_date']) . '</td>' . "\n"; //Expire
                                    echo '<td>';
                                      echo ($cert['current'] ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '<span class="glyphicon glyphicon-ban-circle text-danger"></span>');
                                    echo '</td>' . "\n"; //Current
                                    if($visitor->isAdmin()) { 
                                      echo '<td class="remove">';
                                        echo '<a href="javascript:void(0);" onclick="deleteCert(this,' . $cert['guidecert_id'] . ');">';
                                          echo '<i class="icon-trash glyphicon glyphicon-trash"></i>';
                                        echo '</a>';
                                      echo '</td>' . "\n"; //Remove
                                    }
                                    echo '</tr>' . "\n";
                                  }
                                } else {
                                  ////Print out empty table
                                  echo '<tr>' . "\n";
                                  echo '<td id="first_cell">No Certifications</td>' . "\n"; //Name
                                  echo '<td></td>' . "\n"; //Expire
                                  echo '<td></td>' . "\n"; //Current
                                  echo '<td></td>' . "\n"; //Remove
                                  echo '</tr>' . "\n";
                                } ?>
                              </tbody>
                            </table>
                          </div> <!-- end col-sm-10 -->
                          <?php if($visitor->isAdmin()) { ?>
                            <button class="col-sm-2 btn-group-sm btn btn-warning" id="add_certification" data-toggle="modal" data-target="#modal-add-cert">Add Certification</button>
                          <?php } ?>
                        </div> <!-- end tab-pane -->


                        <div class="tab-pane fade" id="tab11default">
                          <div class="col-sm-12"> 
                            <table class="table table-striped" id="skill-list">
                              <thead>
                                <tr>
                                  <th>Skill</th>
                                  <th style="text-align:center;">Checked Off</th>
                                  <th>Start Date</th>
                                  <th>Notes</th>
                                  <?php if($visitor->isAdmin()) { ?>
                                    <th>Action</th>
                                  <?php } ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                foreach($guideSkills as $skill) {
                                  ////Print out skills
                                  echo '<tr>' . "\n";
                                  echo '<td class="guide_skill_name">' . $skill['skill_name'] . '</td>' . "\n"; //Name

                                  echo '<td style="text-align:center;">';
                                  if (!empty($skill['guideskill_id'])) {
                                    echo '<span class="glyphicon glyphicon-ok text-success"></span>';
                                  } else {
                                    echo '<span class="glyphicon glyphicon-ban-circle text-muted"></span>';
                                    
                                  }                
                                  echo '</td>' . "\n"; //Checked Off

                                  echo '<td>';
                                  if (!empty($skill['guideskill_id'])) {
                                    echo format_date($skill['created_on']);
                                  }
                                  echo '</td>' . "\n"; //Start Date

                                  echo '<td>';
                                  if (!empty($skill['guideskill_id']) || !$visitor->isAdmin()) {
                                    echo $skill['notes'];
                                  } else {
                                    echo '<textarea class="form-control guide_skill_notes" rows="1"></textarea>';
                                  }                
                                  echo '</td>' . "\n"; //Notes

                                  if($visitor->isAdmin()) { 
                                    echo '<td class="remove">';
                                      if (!empty($skill['guideskill_id'])) {
                                        echo '<a href="javascript:void(0);" class="link" onclick="deleteSkill(this,' . $skill['guideskill_id'] . ',' . $skill['skill_id'] . ');">';
                                          echo '<i class="glyphicon glyphicon-trash"></i>';
                                        echo '</a>';
                                      } else {
                                        echo '<a href="javascript:void(0);" class="link" onclick="addSkill(this,' . $skill['skill_id'] . ');">';
                                          echo '<i class="glyphicon glyphicon-plus"></i>';
                                        echo '</a>';
                                      }
                                    echo '</td>' . "\n"; //Remove
                                  } 

                                  echo '</tr>' . "\n";
                                }
                                ?>
                              </tbody>
                            </table>
                          </div> <!-- end col-sm-10 -->
                        </div> <!-- end tab-pane -->




                        <div class="tab-pane fade" id="tab15default">
                          <div class="col-sm-10"> 
                            <table class="table table-striped" id="skill-list">
                              <thead>
                                <tr>
                                  <th>Skill</th>
                                  <th>Start Date</th>
                                  <th>Notes</th>
                                  <?php if($visitor->isAdmin()) { ?>
                                    <th>Remove</th>
                                  <?php } ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                if(isset($skills) && !$skills == false) {
                                  foreach($skills as $skills) {
                                    ////Print out skills
                                    echo '<tr>' . "\n";
                                    echo '<td>' . $skills['skill_name'] . '</td>' . "\n"; //Name
                                    echo '<td>' . format_date($skills['created_on']) . '</td>' . "\n"; //Created On
                                    echo '<td>' . $skills['notes'] . '</td>' . "\n"; //Notes
                                    if($visitor->isAdmin()) { 
                                      echo '<td class="remove">';
                                        echo '<a href="javascript:void(0);" onclick="deleteSkill(this,' . $skills['guideskill_id'] . ');">';
                                          echo '<i class="icon-trash glyphicon glyphicon-trash"></i>';
                                        echo '</a>';
                                      echo '</td>' . "\n"; //Remove
                                    } 
                                    echo '</tr>' . "\n";
                                  }
                                } else {
                                  ////Print out empty table
                                  echo '<tr>' . "\n";
                                  echo '<td id="first_cell">No current skills</td>' . "\n"; //Name
                                  echo '<td></td>' . "\n"; //Expire
                                  echo '<td></td>' . "\n"; //Current
                                  echo '<td></td>' . "\n"; //Remove
                                  echo '</tr>' . "\n";
                                } ?>
                              </tbody>
                            </table>
                          </div> <!-- end col-sm-10 -->
                          <?php if($visitor->isAdmin()) { ?>
                            <button class="col-sm-2 btn-group-sm btn btn-warning" id="add_skill" data-toggle="modal" data-target="#modal-add-skill">Add Guide Skill</button>
                          <?php } ?>
                        </div> <!-- end tab-pane -->




                        <div class="tab-pane fade" id="tab12default">
                          <div class="col-sm-12"> 
                            <table class="table table-striped" id="river-list">
                              <thead>
                                <tr>
                                  <th>River</th>
                                  <th style="text-align:center;">Checked Off</th>
                                  <th>Start Date</th>
                                  <th>Notes</th>
                                  <?php if($visitor->isAdmin()) { ?>
                                    <th>Action</th>
                                  <?php } ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                foreach($riverAwareness as $river) {
                                  ////Print out rivers
                                  echo '<tr>' . "\n";
                                  echo '<td class="guide_river_name">' . $river['longname'] . '</td>' . "\n"; //Name

                                  echo '<td style="text-align:center;">';
                                  if (!empty($river['guideriver_id'])) {
                                    echo '<span class="glyphicon glyphicon-ok text-success"></span>';
                                  } else {
                                    echo '<span class="glyphicon glyphicon-ban-circle text-muted"></span>';
                                    
                                  }                
                                  echo '</td>' . "\n"; //Checked Off

                                  echo '<td>';
                                  if (!empty($river['guideriver_id'])) {
                                    echo format_date($river['created_on']);
                                  }
                                  echo '</td>' . "\n"; //Start Date

                                  echo '<td>';
                                  if (!empty($river['guideriver_id']) || !$visitor->isAdmin()) {
                                    echo $river['notes'];
                                  } else {
                                    echo '<textarea class="form-control guide_river_notes" rows="1"></textarea>';
                                  }                
                                  echo '</td>' . "\n"; //Notes

                                  if($visitor->isAdmin()) { 
                                    echo '<td class="remove">';
                                      if (!empty($river['guideriver_id'])) {
                                        echo '<a href="javascript:void(0);" class="link" onclick="deleteRiver(this,' . $river['guideriver_id'] . ',' . $river['rivertrip_id'] . ');">';
                                          echo '<i class="glyphicon glyphicon-trash"></i>';
                                        echo '</a>';
                                      } else {
                                        echo '<a href="javascript:void(0);" class="link" onclick="addRiver(this,' . $river['rivertrip_id'] . ');">';
                                          echo '<i class="glyphicon glyphicon-plus"></i>';
                                        echo '</a>';
                                      }
                                    echo '</td>' . "\n"; //Remove
                                  } 

                                  echo '</tr>' . "\n";
                                }
                                ?>
                              </tbody>
                            </table>
                          </div> <!-- end col-sm-10 -->
                        </div> <!-- end tab-pane -->

                        <div class="tab-pane fade" id="tab10default">
                          <div class="col-sm-10"> 

                            <div class="notes-notes" id="notes-list">
                             <!--  <div class="notes-notes"> -->
                                <?php 
                                if(isset($notes) && !$notes == false) {
                                foreach($notes as $note): ?>
                                <blockquote>
                                  <p><?php echo htmlentities( stripslashes($note['user_note']) ); ?></p>
                                  <small>
                                    <?php echo htmlentities($note['posted_by_name']);
                                    echo '<em> at ' . $note['created_on'] . ($note['public'] =='Y' ? ' (public)' : ' (private)') . "   " . '</em>';
                                    if($visitor->isAdmin()) { 
                                      echo '<a href="javascript:void(0);" onclick="deleteNote(this,' . $note['usernotes_id'] . ');">';
                                        echo '<i class="icon-trash glyphicon glyphicon-trash"></i>';
                                      echo '</a>';
                                    }
                                    ?>
                                  </small>
                                </blockquote>
                                <?php endforeach;
                                } else {
                                  echo '<blockquote><p>No User Notes</p></blockquote>' . "\n";
                                } ?>
                              <!-- </div> -->
                            </div>
                          </div> <!-- end col-sm-10 -->
                          <?php if($visitor->isAdmin()) { ?>
                            <button class="col-sm-2 btn-group-sm btn btn-warning" id="add_note" data-toggle="modal" data-target="#modal-add-note">Add Note</button>
                          <?php } ?>
                        </div> <!-- end tab-pane -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php'; ?>

    <!--MODAL BOXES-->

    <?php include $_SERVER['DOCUMENT_ROOT'].'/users/userGuide-modals.php'; ?>

    <script type="text/javascript" src="/assets/js/sha512.js"></script>
    <script src="/OGLibrary/js/guide-data.js" type="text/javascript" charset="utf-8"></script>
    <script src="/OGLibrary/js/user-data.js" type="text/javascript" charset="utf-8"></script>
    <script src="/OGLibrary/js/ogengine.js" type="text/javascript" charset="utf-8"></script>

    <script type="text/javascript">
    $(document).ready(function() {

      //click update button to update user profile
      $('#btn-update-profile').click(function(event) {
         ogengine.removeErrorMessages();
         updateUserProfile();
      });

      //click update button to update user details
      $('#btn-update-user').click(function(event) {
         ogengine.removeErrorMessages();
         updateUserDetails();
      });

      //click add button to add an Phone number
      $('#btn-add-phone').click(function(event) {
         ogengine.removeErrorMessages();
         addPhone();
      });

      //click add button to add an Emergency Contact
      $('#btn-add-address').click(function(event) {
         ogengine.removeErrorMessages();
         addAddress();
      });

      //click add button to add an Emergency Contact
      $('#btn-add-ec').click(function(event) {
         ogengine.removeErrorMessages();
         addEC();
      });

      //click update button to update user weaknesses
      $('#btn-update-medical').click(function(event) {
         ogengine.removeErrorMessages();
         updateUserMed();
      });

      //click add button to add guide details
      $('#btn-add-guide').click(function(event) {
         ogengine.removeErrorMessages();
         addGuideDetails();
      });

      //click update button to update guide details
      $('#btn-update-guide').click(function(event) {
         ogengine.removeErrorMessages();
         updateGuideDetails();
      });

      //click add button to add Certification
      $('#btn-add-cert').click(function(event) {
         ogengine.removeErrorMessages();
         addCert();
      });

      //click cancel button clear Modal
      $('[data-dismiss=modal]').on('click', function (e) {
        ogengine.clearModal($(this));

      });

      //click add button to add Pay Rate
      $('#btn-add-payrate').click(function(event) {
         ogengine.removeErrorMessages();
         addPayRate();
      });

      //click add button to add Note
      $('#btn-add-note').click(function(event) {
         ogengine.removeErrorMessages();
         addUserNote();
      });

      //click change button to change password
      $('#btn-change-pass').click(function(event) {
         ogengine.removeErrorMessages();
         changeUserPassword();
      });


    } );
    </script>

  </body>
</html>
