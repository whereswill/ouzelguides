<?php

/**
 * OuzelGuides - Ouzel Outfitters Guide Portal
 *
 * @author Will Sharp
 * @link   http://www.oregonrafting.com
 */

/**
 * Role functions.
 */

    /**
     * Get all of given trip type from existing trips before allowing to delete
     * @return All trip ids or null if trip with given type doesn't exist.
     */
    function getRoleType($role_type) {
	
		switch ($role_type):
		    case 0:
		        $result = "Not designated";
		        break;
		    case 1:
		        $result = "Guide";
		        break;
		    case 2:
		        $result = "Other";
		        break;
		    default:
		        $result = "Unknown";
		endswitch;
		
		return $result;

    }

    function getRoles($type_1, $type_2) {
	
		$db = ASDatabase::getInstance();
	
		$result = $db->select("SELECT `role_id`, `role_name`, `default_amount` FROM `roles` WHERE `role_type` BETWEEN $type_1 AND $type_2 AND `active` = 'Y' ORDER BY `dd_order`");
		
		return $result;

    }

    function getRoleName($role_id_fk) {

		$db = ASDatabase::getInstance();

		$result = $db->select("SELECT `role_name` FROM `roles` WHERE `role_id` = :role_id_fk", array( "role_id_fk" => $role_id_fk ));
		$result = $result[0];
		
		$result = htmlentities($result['role_name']);
		
		return $result;

    }

  /**
   * Get total number of guide or trip events that have this role assigned to them
   * @return total number.
   */
  function eventsWithThisRole($role_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `trip_id_fk`
              FROM `guide_events`
              WHERE `role_id_fk` = :role_id";

    $guide_result = $db->select($query, array( 'role_id' => $role_id));

    $query = "SELECT `trip_id_fk`
              FROM `other_events`
              WHERE `role_id_fk` = :role_id";

    $other_result = $db->select($query, array( 'role_id' => $role_id));

		return count($guide_result) + count($other_result);
	}

  /**
   * Determines whether the Role is editable or if it is a system role
   * @return true or false.
   */
  function isRoleEditable($role_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `editable`
              FROM `roles`
              WHERE `role_id` = :role_id";

    $role_result = $db->select($query, array( 'role_id' => $role_id));
		$role_result = $role_result[0];

		//echo $role_result['editable'];

		if ($role_result['editable'] == "Y") {
			return true;
		} else {
			return false;
		}
	}

  /**
   * Rule: Swampers do not get Base Pay - if there is an arrangement to pay them it has to be added as an adjustment
   * @return true or false.
   */
  function isSwamper($role_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `role_name`
              FROM `roles`
              WHERE `role_id` = :role_id";

    $role_result = $db->select($query, array( 'role_id' => $role_id));
		$role_result = $role_result[0];
		
		//echo $role_result['role_name'];

		if ($role_result['role_name'] == "Swamper") {
			return true;
		} else {
			return false;
		}
	}

  /**
   * Rule: TL or sub-TL gets TL pay
   * @return true or false.
   */
  function isTL($role_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `role_name`
              FROM `roles`
              WHERE `role_id` = :role_id";

    $role_result = $db->select($query, array( 'role_id' => $role_id));
		$role_result = $role_result[0];

		//echo $role_result['role_name'];

		if ($role_result['role_name'] == "TL") {
			return true;
		} else {
			return false;
		}

	}

	 /**
   * Rule: TL or sub-TL gets TL pay
   * @return true or false.
   */
  function 	isGuideRole($role_id) {

		$db = ASDatabase::getInstance();

    $query = "SELECT `role_type`
              FROM `roles`
              WHERE `role_id` = :role_id";

    $role_result = $db->select($query, array( 'role_id' => $role_id));
		$role_result = $role_result[0];

		if ($role_result['role_type'] == 1) {
			return true;
		} else {
			return false;
		}

	}

