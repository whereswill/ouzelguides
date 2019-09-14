<?php

/**
 * OuzelGuides - Ouzel Outfitters Guide Portal
 *
 * @author Will Sharp
 * @link   http://www.oregonrafting.com
 */

/**
 * Trip Type class.
 */

    /**
     * Get all of given trip type from existing trips before allowing to delete
     * @return All trip ids or null if trip with given type doesn't exist.
     */
    function getAllTypesinTrips($trip_type_fk) {

		$db = ASDatabase::getInstance();
		
        $query = "SELECT `trip_id`
                    FROM `trips`
                    WHERE `triptype_id` = :trip_type_fk";

        $result = $this->db->select($query, array( 'trip_type_fk' => $trip_type_fk));

        if ( count ( $result ) > 0 )
            return $result;
        else
            return null;
    }

    /**
     * Determines whether the Trip Type is editable or if it is a system type
     * @return true or false.
     */
    function isTypeEditable($triptype_id) {

		$db = ASDatabase::getInstance();

        $query = "SELECT `editable`
                    FROM `trip_types`
                    WHERE `triptype_id` = :triptype_id";

        $role_result = $db->select($query, array( 'triptype_id' => $triptype_id));
		$role_result = $role_result[0];

		if ($role_result['editable'] == "Y") {
			return true;
		} else {
			return false;
		}
	}