<?php

/**
 * OuzelGuides - Ouzel Outfitters Guide Portal
 *
 * @author Will Sharp
 * @link   http://www.oregonrafting.com
 */

/**
 * Pay Rate functions.
 */

	/**
	* Get total number of guides that have this rate assigned to them
	* @return total number.
	*/
	function guidesWithThisRate($payrate_id) {

		$db = ASDatabase::getInstance();

		$query = "SELECT `user_id_fk`
		            FROM `guide_payrates`
		            WHERE `payrate_id_fk` = :payrate_id";

		$result = $db->select($query, array( 'payrate_id' => $payrate_id));

		return count($result);
	}

	/**
	* Determine if any guides have this rate assigned to them
	* @return true or false
	*/
	function isRateAssigned($payrate_id) {

		$db = ASDatabase::getInstance();

		$query = "SELECT `user_id_fk`
		            FROM `guide_payrates`
		            WHERE `payrate_id_fk` = :payrate_id";

		$result = $db->select($query, array( 'payrate_id' => $payrate_id));

		if (count($result) > 0) {
		return true;
		} else {
		return false;
		}
	}
?>