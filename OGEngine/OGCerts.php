<?php

/**
 * Certification functions.
 */

	/**
	* Determine if any guides have this cert assigned to them AND it is current
	* @return number of guides
	*/
	function isCertAssigned($certrate_id) {

		$db = ASDatabase::getInstance();

	   	$query = "SELECT `user_id_fk`, `exp_date`
	               FROM `guide_certs`
	               WHERE `certrate_id_fk` = :certrate_id";

	   	$result = $db->select($query, array( 'certrate_id' => $certrate_id));

		$today = new DateTime();
		$count = 0;

		foreach($result as $r) {
			$expire = new DateTime($r['exp_date']);
			if($today < $expire) {$count++;}
		}

		return $count;
		
	}