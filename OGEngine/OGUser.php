<?php
 
/**
 * User class
 *
 * This is a class to deal with Users
 *
 * @author Will Sharp <whereswill@bendcable.com>
 * @copyright 2014 Back Alley
 */
class User
{

	/**
   * @var ID of Trip instance
   */
	public $user_id;
	
	/**
   * @var Instance of ASDatabase class
   */
  private $db = null;
	
	function __construct() { 
		          
		//$this->user_id = $inst_user_id; 
		
    $this->db = ASDatabase::getInstance(); 
		          
	}

	public function set_user_id($new_userId) { 
		
		$this->user_id = $new_userId;
		  
	}
	
	public function get_user_id() {

		return $this->user_id;
		
	}

  /**
   * Get all user details including email, username and last_login
   * @return User details or null if user with given id doesn't exist.
   */
  public function getAll() {
      $query = "SELECT `as_users`.`email`, `as_users`.`username`,`as_users`.`last_login`, `as_user_details`.*
                FROM `as_users`, `as_user_details`
                WHERE `as_users`.`user_id` = :id
                AND `as_users`.`user_id` = `as_user_details`.`user_id`";

      $result = $this->db->select($query, array( 'id' => $this->user_id ));

      if ( count ( $result ) > 0 )
          return $result[0];
      else
          return false;
  }
    
  /**
   * Get user details (First Name, Last Name, Address and Phone)
   * @return array User details array.
   */
  public function getUserDetails() {
      $result = $this->db->select(
                  "SELECT * FROM `as_user_details` WHERE `user_id` = :id",
                  array ("id" => $this->user_id)
                );

    if (count($result) > 0) {
      return $result;
    } else {
      return false;
    }
  }

  
  /**
   * Get user addresses (type, care of, street 1, street 2, city, state, zip)
   * @return array User addresses array.
   */
  public function getUserAddresses() {
      $result = $this->db->select(
                  "SELECT * FROM `addresses` WHERE `user_id_fk` = :id",
                  array ("id" => $this->user_id)
                );

    if (count($result) > 0) {
      return $result;
    } else {
      return false;
    }
  }

  
  /**
   * Get user phones (type, phone number, order)
   * @return array User phone number array.
   */
  public function getUserECs() {
      $result = $this->db->select(
                  "SELECT * FROM `emergency_contacts` WHERE `user_id_fk` = :id",
                  array ("id" => $this->user_id)
                );

    if (count($result) > 0) {
      return $result;
    } else {
      return false;
    }
  }

  
  /**
   * Get user phones (type, phone number, order)
   * @return array User phone number array.
   */
  public function getUserPhones() {
      $result = $this->db->select(
                  "SELECT * FROM `phone_numbers` WHERE `user_id_fk` = :id ORDER BY `best_order` ASC",
                  array ("id" => $this->user_id)
                );

    if (count($result) > 0) {
      return $result;
    } else {
      return false;
    }
  }

	/**
   * Get name of User with the passed id
   * @return concatenated string
   */
	function getUserName() {

		$obj_id = $this->user_id;

		$result = $this->db->select("SELECT `user_id`,`first_name`,`last_name` FROM `as_user_details` WHERE `user_id` = :user_id_fk", array( "user_id_fk" => $obj_id ));

    if ($result[0]['first_name'] && $result[0]['last_name']) {
      $result = $result[0];
      $result = htmlentities($result['first_name'] . " " . $result['last_name']);
      return $result;
    } else {
      $result = $this->db->select("SELECT `username` FROM `as_users` WHERE `user_id` = :user_id", array( "user_id" => $obj_id ));
      return htmlentities($result[0]['username']);
    }

  }

  /**
   * Get all active users
   * @return user_id, first and last names
   */
  function getActiveUsers() {

    $query = "SELECT as_users.username, as_user_details.user_id, as_user_details.first_name, as_user_details.last_name 
              FROM `as_user_details` 
              INNER JOIN `as_users` ON as_users.user_id = as_user_details.user_id
              WHERE as_user_details.active = 'Y' 
              ORDER BY as_user_details.first_name ASC";

    $result = $this->db->select($query);

    if (count($result) > 0) {
      foreach ($result as $key => $value) {
        if ($value['first_name'] == NULL) {
          $result[$key]['name'] = $value['username'];
        } else {
          $result[$key]['name'] = $value['first_name'] . " " . $value['last_name'];
        }
      }
      return $result;
    } else {
      return false;
    }
  }

  /**
   * Inserts phone number into database.
   * @param int $phoneDetails Id of phone being added
   * @param array $phoneDetails of contact information
   * @return array of inserted data or failure
   */
  function addPhone($phoneData) {

    $obj_id = $this->user_id; 

    $err = $this->db->insert("phone_numbers",  array(
        "user_id_fk"    => $obj_id,
        "phone_type"    => $phoneData['phone_type'],
        "phone_number"  => $phoneData['phone_number'],
        "best_order"    => $phoneData['best_order'],
        "updated_by"    => $phoneData['updated_by']
    ));

    $phone_id = $this->db->lastInsertId();

    if ($err) {
      echo json_encode(array(
        "status"        => "success",
        "phone_id"      => $phone_id,
        "phone_type"    => $phoneData['phone_type'],
        "phone_number"  => $phoneData['phone_number'],
        "best_order"    => $phoneData['best_order']
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: Users phone number was not added"
      ));
    }
  }

  /**
   * Deletes phone number from database.
   * @param int $phone_id Id of number being deleted
   */
  function deletePhone($phone_id) {

    $this->db->delete("phone_numbers", "phone_id = :phone_id", array( "phone_id" => $phone_id));

  }

  /**
   * Inserts Address into database.
   * @param int $userId Id of user for whom address is being added
   * @param array $addressDetails of contact information
   * @return array of results
   */
  function addAddress($addressDetails) {

    $obj_id = $this->user_id; 

    $err = $this->db->insert("addresses",  array(
        "user_id_fk"    => $obj_id,
        "address_type"  => $addressDetails['address_type'],
        "care_of"       => $addressDetails['care_of'],
        "street_one"    => $addressDetails['street_one'],
        "street_two"    => $addressDetails['street_two'],
        "city"          => $addressDetails['city'],
        "state"         => $addressDetails['state'],
        "postal_code"   => $addressDetails['postal_code'],
        "updated_by"    => $addressDetails['updated_by']
    ));

    $address_id = $this->db->lastInsertId();

    $query = "SELECT *
              FROM `addresses` 
              WHERE  `address_id` = :address_id";

    $result = $this->db->select($query, array( "address_id" => $address_id ));

    if ($err) {
      echo json_encode(array(
        "status"         => "success",
        "msg"            => "Success! Address was added successfully",
        "address_id"     => $address_id,
        "address_type"   => $result[0]['address_type'],
        "care_of"        => $result[0]['care_of'],
        "street_one"     => $result[0]['street_one'],
        "street_two"     => $result[0]['street_two'],
        "city"           => $result[0]['city'],
        "state"          => $result[0]['state'],
        "postal_code"    => $result[0]['postal_code']
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: Emergency Contact was not added"
      ));
    }
  }

  /**
   * Deletes a users address from database.
   * @param int $address_id of address being deleted
   */
  function deleteAddress($address_id) {

    $this->db->delete("addresses", "address_id = :address_id", array( "address_id" => $address_id));

  }

  /**
   * Inserts Emergency Contact into database.
   * @param int $userId Id of user for whom cert is being added
   * @param array $ecDetails of contact information
   * @return date of expiration
   */
  function addEC($ecDetails) {

    $obj_id = $this->user_id; 

    $err = $this->db->insert("emergency_contacts",  array(
        "user_id_fk"  => $obj_id,
        "ec_relation" => $ecDetails['ec_relation'],
        "ec_name"     => $ecDetails['ec_name'],
        "ec_phone"    => $ecDetails['ec_phone'],
        "ec_phone2"   => $ecDetails['ec_phone2'],
        "ec_email"    => $ecDetails['ec_email'],
        "updated_by"  => $ecDetails['updated_by']
    ));

    $ec_id = $this->db->lastInsertId();

    if ($err) {
      echo json_encode(array(
        "status"      => "success",
        "ec_id"       => $ec_id,
        "ec_relation" => $ecDetails['ec_relation'],
        "ec_name"     => $ecDetails['ec_name'],
        "ec_phone"    => $ecDetails['ec_phone'],
        "ec_phone2"   => $ecDetails['ec_phone2'],
        "ec_email"    => $ecDetails['ec_email']
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: Emergency Contact was not added"
      ));
    }
  }

  /**
   * Deletes emergency contact from database.
   * @param int $ec_id Id of contact being deleted
   */
  function deleteEC($ec_id) {

    $this->db->delete("emergency_contacts", "ec_id = :ec_id", array( "ec_id" => $ec_id));

  }

  /**
   * Updates username or email address
   * @param array $updateData Associative array where keys are database fields that need
   * to be updated and values are new values for provided database fields.
   */
  public function updateProfile($data) {
    $err = $this->db->update(
        "as_users", 
        $updateData = array(
        'username'    => $data['username'],
        'email'       => $data['email']
        ), 
        "`user_id` = :id",
        array( "id" => $this->user_id )
    );

    if ($err) {
      echo json_encode(array(
        "status"  => "success",
        "msg"     => "User was updated successfully"
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: User update was not completed"
      ));
    }
  }

  /**
   * Updates user details
   * @param array $updateData Associative array where keys are database fields that need
   * to be updated and values are new values for provided database fields.
   */
  public function updateUser($data) {
    $err = $this->db->update(
        "as_user_details", 
        $updateData = array(
        'active'      => $data['active'],
        'first_name'  => $data['first_name'],
        'middle_name' => $data['middle_name'],
        'last_name'   => $data['last_name'],
        'nickname'    => $data['nickname'],
        'birthdate'   => $data['birthdate'],
        't_size'      => $data['t_size'],
        'star_sign'   => $data['star_sign']
        ), 
        "`user_id` = :id",
        array( "id" => $this->user_id )
    );

    if ($err) {
      echo json_encode(array(
        "status"  => "success",
        "msg"     => "User was updated successfully"
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: User update was not completed"
      ));
    }
  }

  /**
   * Updates user info.
   * @param array $updateData Associative array where keys are database fields that need
   * to be updated and values are new values for provided database fields.
   */
  public function updateInfo($updateData) {
    $this->db->update(
                "as_users", 
                $updateData, 
                "`user_id` = :id",
                array( "id" => $this->user_id )
           );
  }

  /**
   * Updates user details.
   * @param array $updateData Associative array where keys are database fields that need
   * to be updated and values are new values for provided database fields.
   */
  public function updateDetails($updateData) {
    $this->db->update(
                "as_user_details", 
                $updateData, 
                "`user_id` = :id",
                array( "id" => $this->user_id )
           );
  }

  /**
   * Update user's Meds
   * @param $data User data from users "edit user" form
   */
  public function updateMeds($data) {

    //unpack data array
    $medical   = $data['medical'];
    $allergy   = $data['allergy'];
    $dietary   = $data['dietary'];

    $err = $this->db->update(
      "as_user_details", 
      $updateData = array(
        "medical"       => $medical,
        "allergy"       => $allergy,
        "dietary"       => $dietary
      ), 
      "`user_id` = :id",
      array( "id" => $this->user_id )
    );

    if ($err) {
      echo json_encode(array(
        "status"  => "success",
        "msg"     => "Medical was updated successfully"
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: Medical update was not completed"
      ));
    }

  }

  /**
   * Update user's password
   * @param $data User data from users "change password" form
   */
  public function changePassword($password) {

    // update password only if "password" field is hashed
    // and password is different than current password
    if ( $password != hash('sha512','') ) {
        $password = $this->_hashPassword($password);
        // if ( $currInfo['password'] != $password )
        //     $userInfo['password'] = $password;
    }

    $err = $this->db->update(
      "as_users", 
      $updateData = array(
        "password"   => $password
      ), 
      "`user_id` = :id",
      array( "id" => $this->user_id )
    );

    if ($err) {
      echo json_encode(array(
        "status"  => "success",
        "msg"     => "Password was updated successfully"
      ));
    } else {
      echo json_encode(array(
        "status"  => "failure",
        "msg"     => "Db error: Password update was not completed"
      ));
    }

  }

  /**
   * Changes user's role. If user's role was editor it will be set to user and vice versa.
   * @return string New user role.
   */
  public function changeRole() {
      $role = $_POST['role'];

      $result = $this->db->select("SELECT * FROM `as_user_roles` WHERE `role_id` = :r", array( "r" => $role ));

      if(count($result) == 0)
          return;

      $this->updateInfo(array( "user_role" => $role ));

      return $result[0]['role'];
  }

	/**
  * Determine if user is active guide
  * @return false if no, true if yes
  */
  function isActiveGuide() {

		$obj_id = $this->user_id;

    $query = "SELECT `user_id_fk`
						  FROM `guide_details` 
						  WHERE  `user_id_fk` = :user_id_fk
						  AND `active_bool` = 'Y'";

    $result = $this->db->select($query, array( "user_id_fk" => $obj_id ));

		if (count($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

  /**
  * Determine if user is active user
  * @return false if no, true if yes
  */
  function isActiveUser() {

    $obj_id = $this->user_id;

    $query = "SELECT `user_id`
              FROM `as_user_details` 
              WHERE  `user_id` = :user_id
              AND `active` = 'Y'";

    $result = $this->db->select($query, array( "user_id" => $obj_id ));

    if (count($result) > 0) {
      return true;
    } else {
      return false;
    }
  }

	/**
  * Get alphebetized list of user ids from trips and timesheets that are approved
  * and occur on or before the passed end date
  * @return array of unique, alphabetized and newly keyed user ids
  */
  function getApprovedUsers($date) {

		$obj_id = $this->user_id;

		//Get all trips that are approved before end date
		$trip = new Trip();
		$app_trips = $trip->getApprovedTripsBeforeEnd($date);

    $app_users = array();
    //Get list of users from each approved trip
		foreach ($app_trips as $trip_key) {
			$query1 = "SELECT DISTINCT `user_id_fk`
		  					FROM `guide_events`
		  					WHERE  `trip_id_fk` = :trip_id";

    	$trip_result = $this->db->select($query1, array( "trip_id" => $trip_key['trip_id']));
    	$app_users = array_unique(array_merge($app_users,$trip_result), SORT_REGULAR);

			$query2 = "SELECT DISTINCT `user_id_fk`
		  					FROM `other_events`
		  					WHERE  `trip_id_fk` = :trip_id
		  					AND timesheet_id_fk IS NULL";

    	$sheet_result = $this->db->select($query2, array( "trip_id" => $trip_key['trip_id']));
    	$app_users = array_unique(array_merge($app_users,$sheet_result), SORT_REGULAR);
		}

		//Get all timesheets that are approved before end date
		$timesheet = new Timesheet();
		$app_timesheets = $timesheet->getApprovedTimesheetsBeforeEnd($date);

		//Get users for each timesheet and add to users array
		$query3 = "SELECT `user_id_fk`
		  				 FROM `timesheets`
		  				 WHERE `timesheet_id` = :timesheet_id";

		$time_users = array();
		foreach ($app_timesheets as $value) {
    	$time_result = $this->db->select($query3, array( "timesheet_id" => $value));
			$time_users[]['user_id_fk'] = $time_result[0]['user_id_fk'];
		}
    
    $app_users = array_values(array_unique(array_merge($app_users,$time_users), SORT_REGULAR));


		//Get user names for ids and add to array
		$guide = new Guide();
		foreach ($app_users as $key => $value) {
			$guide->set_guide_id($value['user_id_fk']);
			$name = $guide->getUserName();
			$app_users[$key]['name'] = $name;
		}

		///Sort array alphabetically by First name
		usort($app_users, function($a, $b) {
	    return strcasecmp( $a['name'], $b['name'] );
		});

		return $app_users;
    
	}//End function

  /**
   * Get all users and guides that got paid for a given year
   * @return array user_id
   */
  public function getAllSeasonUserGuides($year) {

    $query = "SELECT DISTINCT `user_id_fk`
               FROM `approvals`
               WHERE  locked IS NOT NULL
               AND EXTRACT(YEAR FROM `event_date`) = :year";

    $result = $this->db->select($query, array( "year" => $year));

    if (count($result) > 0) {
      return $result;
    } else {
      return false;
    }
  }

	/**
  * Get alphebetized list of user ids from trips and timesheets that are locked
  * and occur on the passed end date
  * @return array of unique, alphabetized and newly keyed user ids
  */
  function getLockedUsers($date) {

		$query2 = "SELECT DISTINCT `user_id_fk`
							 FROM `approvals`
							 WHERE  locked = :end_date";

    $locked_users = $this->db->select($query2, array( "end_date" => $date));

		//Get user names for ids and add to array
		$guide = new Guide();
		foreach ($locked_users as $key => $value) {
			$guide->set_guide_id($value['user_id_fk']);
			$name = $guide->getUserName();
			$locked_users[$key]['name'] = $name;
		}

		///Sort array alphabetically by First name
		usort($locked_users, function($a, $b) {
	    return strcasecmp( $a['name'], $b['name'] );
		});

		return $locked_users;
    
	}//End function


	/**
  * Determine if user has any approved trips or timesheets before end date
  * @return false if no, true if yes
  */
  function approvedBeforeEnd($date) {

		$obj_id = $this->user_id;

    $query = "SELECT approvals.approval_id
		  				FROM `trips` 
		  				JOIN `approvals` ON trips.trip_id = approvals.trip_id_fk
		  				WHERE  approvals.user_id_fk = :user_id_fk
		  				AND trips.takeout_date <= :end_date
		  				AND approvals.timesheet_id_fk IS NULL";

    $result = $this->db->select($query, array( "user_id_fk" => $obj_id, "end_date" => $date ));

    if (count($result) > 0) {
    	$approved_ids = $result;
    }

    $query_sheets = "SELECT approvals.approval_id
										 FROM `other_events` 
		  							 JOIN `approvals` ON other_events.otherevent_id = approvals.otherevent_id_fk
		  							 WHERE  other_events.user_id_fk = :user_id_fk
		  							 AND other_events.event_date <= :end_date
		  							 AND approvals.timesheet_id_fk IS NOT NULL";

  	$result_sheets = $this->db->select($query_sheets, array( "user_id_fk" => $obj_id, "end_date" => $date ));

  	if (count($result_sheets) > 0 && count($result) > 0) {
			foreach ($result_sheets as $key) {
      	$approved_ids[] = $key;
   		}
  	} elseif (count($result_sheets) > 0) {
    	$approved_ids = $result_sheets;
  	} else {
  		return false;
  	}
    
	}//End function

  /**
   * Get a guides locked w/h for a given year
   * @return trip_id.
   */
  function getLockedWH($year) {

    $obj_id = $this->guide_id;

    $query = "SELECT DISTINCT `timesheet_id_fk`
              FROM `approvals`
              WHERE `trip_id_fk` IS NULL
              AND `locked` IS NOT NULL
              AND `user_id_fk` = :user_id_fk
              AND EXTRACT(YEAR FROM `event_date`) = :year
              ORDER BY `event_date` ASC";

    $result = $this->db->select($query, array( 'user_id_fk' => $obj_id, 'year' => $year));

    return $result;

  }

  /**
   * Return an array of pay breakdown for a guide for a given year
   * @param year
   * @return array breakdown of pay for season
   */
  public function getYTDWHPay($year) {

    $obj_id = $this->user_id;

    $ytd_wh = $this->getLockedWH($year);

    $timesheet = new Timesheet();

    $wh_pay = 0;

    $tot_wh_days = 0;

    foreach ($ytd_wh as $t) {

      $timesheet->set_sheet_id($t['timesheet_id_fk']);
      $whtp = $timesheet->getUserTimesheetPay();

      $wh_pay += $whtp['wh_pay'];
      $tot_wh_days += $whtp['wh_days'];

    }

    $user_wh_pay = array(
      'wh_pay'   => $wh_pay,
      'tot_wh_days'  => $tot_wh_days
    );

    return $user_wh_pay;
  }

  /**
   * Get current user's role.
   * @return string Current user's role.
   */
  public function getRole() {
      $result = $this->db->select(
                    "SELECT `as_user_roles`.`role` as role 
                     FROM `as_user_roles`,`as_users`
                     WHERE `as_users`.`user_role` = `as_user_roles`.`role_id`
                     AND `as_users`.`user_id` = :id",
                     array( "id" => $this->user_id)
                  );

      return $result[0]['role'];
  }
    
  /**
   * Hash provided password.
   * @param string $password Password that needs to be hashed.
   * @return string Hashed password.
   */
  private function _hashPassword($password) {
      $register = new ASRegister();
      return $register->hashPassword($password);
  }


}//End Class

?>