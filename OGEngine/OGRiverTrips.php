<?php

/**
 * Ouzelguides - Guide Portal
 *
 * @author Will Sharp
 * @link   http://mstojanovic.net/as
 */

/**
 * River Trip class.
 */
class RiverTrip {

  /**
   * @var Instance of ASDatabase class itself
   */
  private $db = null;

  /**
   * Class constructor
   */
  function __construct() {
      $this->db = ASDatabase::getInstance();
  }

  /**
   * Return long name and id for all river trips.
   * @return string Long Name string and id.
   */
  public function getTrips() {

      $query = "SELECT `rivertrip_id`, `longname`, `rivertrip_name`
                FROM `river_trips`
                ORDER BY `longname` ASC";

      $result = $this->db->select($query);

      return $result;
  }

  /**
   * Return long name from river trips table.
   * @param river trip id
   * @return string Long Name string.
   */
  public function getLongName($rivertrip_id) {

      $query = "SELECT `longname`
                FROM `river_trips`
                WHERE `rivertrip_id` = :rivertrip_id";

      $result = $this->db->select($query, array( 'rivertrip_id' => $rivertrip_id));

      return $result[0]['longname'];
  }

  /**
   * Return all drainages from river trips table.
   * @param none
   * @return array Array of all drainages in alpha order.
   */
  public function getDrainages() {

      $query = "SELECT DISTINCT `drainage`
                FROM `river_trips`
                ORDER BY `drainage` ASC";

      $result = $this->db->select($query);

      return $result;
  }

  /**
   * Return  drainage from for a river trip
   * @param rivertrip_id
   * @return string drainage
   */
  public function getRtDrainage($rivertrip_id) {

      $query = "SELECT `drainage`
                FROM `river_trips`
                WHERE `rivertrip_id` = :rivertrip_id";

      $result = $this->db->select($query, array('rivertrip_id' => $rivertrip_id));

      return $result[0]['drainage'];
  }

  /**
   * Return all drainages from river trips table.
   * @param none
   * @return array Array of all drainages in alpha order.
   */
  public function getRiverTrips($drainage, $year) {

      $query = 'SELECT DISTINCT trips.river_trips_fk
                FROM `trips`
                JOIN `river_trips` ON trips.river_trips_fk = river_trips.rivertrip_id
                WHERE river_trips.drainage = :drainage
                AND trips.locked_on IS NOT NULL
                AND YEAR(STR_TO_DATE(trips.takeout_date,"%Y-%c-%e")) = :year';

      $result = $this->db->select($query, array( 'drainage' => $drainage, "year" => $year));

    if (count($result) > 0) {
      return $result;
    } else {
      return false;
    }
  }

  /**
   * Return the guide/guest ratio for a specific river trip given a year
   * @param river_trip_id, selected year
   * @return int Integer representing the guide/guest ratio
   */
  public function getGuideRatio($river_trip, $year) {

    $query = 'SELECT `trip_id`
              FROM `trips`
              WHERE `river_trips_fk` = :river_trip
              AND `locked_on` IS NOT NULL
              AND YEAR(STR_TO_DATE(`takeout_date`,"%Y-%c-%e")) = :year';

    $trips = $this->db->select($query, array('river_trip' => $river_trip, 'year' => $year));

    $trip = new Trip();
    $ratio = 0;
    $tot_guides = 0;
    $tot_guests = 0;
    $tot_swamp = 0;
    $count = count($trips);
    foreach ($trips as $t) {
      $trip->set_trip_id($t['trip_id']);
      $num_guides = $trip->numberOfGuides();
      $tot_guides += $num_guides;
      $num_guest = $trip->numberOfGuests();
      $tot_guests += $num_guest;
      $num_swamp = $trip->numberOfSwampers();
      $tot_swamp += $num_swamp;
      $this_ratio = $num_guest/$num_guides;
      $ratio += $this_ratio;
    }
    
    $guest_ratio = $ratio/$count;

    $ratio_stats = array(
      'tot_guides' => $tot_guides,
      'tot_guests' => $tot_guests,
      'tot_swamp' => $tot_swamp,
      'guest_ratio' => $guest_ratio,
    );

    return $ratio_stats;
  }

  /**
   * Return the an array of trip_ids for paid trips that are a given river trip
   * @param river_trip_id, selected year
   * @return array trip_ids
   */
  public function getLockedRiverTrips($river_trip, $year) {

    $query = 'SELECT `trip_id`
              FROM `trips`
              WHERE `river_trips_fk` = :river_trip
              AND `locked_on` IS NOT NULL
              AND YEAR(STR_TO_DATE(`takeout_date`,"%Y-%c-%e")) = :year';

    $trip_ids = $this->db->select($query, array('river_trip' => $river_trip, 'year' => $year));

    return $trip_ids;
  }

  /**
   * Return the total associated pay for all trips for a specific river trip given a year
   * @param river_trip_id, selected year
   * @return int Integer representing the total pay
   */
  public function getRiverTripPay($river_trip, $year) {

    $query = 'SELECT `trip_id`
              FROM `trips`
              WHERE `river_trips_fk` = :river_trip
              AND `locked_on` IS NOT NULL
              AND YEAR(STR_TO_DATE(`takeout_date`,"%Y-%c-%e")) = :year';

    $trips = $this->db->select($query, array('river_trip' => $river_trip, 'year' => $year));

    $trip = new Trip();
    $river_trip_total = 0;
    $river_trip_guide_total = 0;
    $river_trip_swamp_total = 0;
    $river_trip_assoc_total = 0;
    foreach ($trips as $t) {
      $trip->set_trip_id($t['trip_id']);
      $trip_stats = $trip->getTripPay();
      $river_trip_total += $trip_stats['trip_total'];
      $river_trip_guide_total += $trip_stats['guide_tot'];
      $river_trip_swamp_total += $trip_stats['swamp_tot'];
      $river_trip_assoc_total += $trip_stats['assoc_tot'];
    }

    $pay_stats = array(
      'guide_tot'   => $river_trip_guide_total,
      'swamp_tot'   => $river_trip_swamp_total,
      'assoc_tot'   => $river_trip_assoc_total,
      'trip_total'  => $river_trip_total
    );

    return $pay_stats;
  }

  /**
   * Get all trips that got paid for a river trip for a given year
   * @param year, rivertrip_id
   * @return array trip_id
   */
  public function getAllPaidSeasonRiverTrips($year) {

    $query = 'SELECT DISTINCT `river_trips_fk`
              FROM `trips`
              WHERE `locked_on` IS NOT NULL
              AND EXTRACT(YEAR FROM `takeout_date`) = :year';

    $result = $this->db->select($query, array('year' => $year));

    if (count($result) > 0) {

      foreach ($result as $key => $value) {
        $name = $this->getLongName($value['river_trips_fk']);
        $result[$key]['name'] = $name;
        $drainage = $this->getRtDrainage($value['river_trips_fk']);
        $result[$key]['drainage'] = $drainage;
      }

      ///Sort array alphabetically by First name
      usort($result, function($a, $b) {
        return strcasecmp( $a['name'], $b['name'] );
      });

      return $result;

    } else {
      return false;
    }
  }

  /**
   * Return an array of pay breakdown for a river trip for a given year
   * @param year, rivertrip_id
   * @return array breakdown of pay by river trip for season
   */
  public function getYTDRiverTripPay($year, $rivertrip_id) {

    $ytd_trips = $this->getLockedRiverTrips($rivertrip_id, $year);

    $trip = new Trip();

    $base_pay = 0;
    $tl_pay = 0;
    $sat_pay = 0;
    $bump_pay = 0;
    $rig_pay = 0;
    $shop_pay = 0;
    $other_pay = 0;
    $assoc_pay = 0;
    $cert_pay = 0;
    $bonus_pay = 0;

    $tot_trip_days = 0;

    foreach ($ytd_trips as $t) {

      $trip->set_trip_id($t['trip_id']);
      $gtp = $trip->getTripPayBreakdown();

      $base_pay += $gtp['base_pay'];
      $tl_pay += $gtp['tl_pay'];
      $sat_pay += $gtp['sat_pay'];
      $bump_pay += $gtp['bump_pay'];
      $rig_pay += $gtp['rig_pay'];
      $shop_pay += $gtp['shop_pay'];
      $other_pay += $gtp['other_pay'];
      $assoc_pay += $gtp['assoc_pay'];
      $cert_pay += $gtp['cert_pay'];
      $bonus_pay += $gtp['bonus_pay'];
      $ytd_guide_total += $gtp['ytd_guide_total'];
      $ytd_guide_days += $gtp['ytd_guide_days'];

    }

    $season_river_trip_pay = array(
      'base_pay'  => $base_pay,
      'tl_pay'  => $tl_pay,
      'sat_pay'   => $sat_pay,
      'bump_pay'  => $bump_pay,
      'rig_pay'   => $rig_pay,
      'shop_pay'  => $shop_pay,
      'other_pay'   => $other_pay,
      'assoc_pay'   => $assoc_pay,
      'cert_pay'  => $cert_pay,
      'bonus_pay'   => $bonus_pay,
      'ytd_guide_total'   => $ytd_guide_total,
      'ytd_guide_days'  => $ytd_guide_days
    );

    return $season_river_trip_pay;
  }

}
