<?php

/**
 * OuzelGuides - Ouzel Outfitters Guide Portal
 *
 * @author Will Sharp
 * @link   http://www.oregonrafting.com
 */

/**
 * Skills class.
 */
class Skill {

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
     * Inserts skill into database.
     * @param string $name of skill
     * @param string $description of skill
     * @return string JSON encoded string that consist of 3 fields:
     * id,name and description
     */
    public function addSkill($details) {

        $err = $this->db->insert("skills",  array(
            "skill_name"     => $details['name'],
            "description"    => $details['description']
        ));

        if ($err) {
          echo json_encode(array(
            "status"            => "success",
            "msg"               => "Success! Skill was added successfully",
            "skill_id"  		=> $this->db->lastInsertId(),
            "skill_name"        => $details['name'],
            "skill_description" => $details['description']
          ));
        } else {
          echo json_encode(array(
            "status"  => "failure",
            "msg"     => "Db error: Skill was not added"
          ));
        }

    }

    /**
     * Update skill details
     * @param $data Skill data from "edit skill" form
     */
    public function updateSkill($data) {

        $err = $this->db->update (
            "skills",
            array(
                'skill_name'   => $data['name'],
                'description'  => $data['description']
            ),
            "`skill_id` = :id",
            array( "id" => $data['id'] )
            );

        if ($err){ 
            echo json_encode(array(
                "status"  => "success",
                "msg"     => "Success! Skill was updated successfully"
            ));
        } else {
            echo json_encode(array(
                "status"  => "error",
                "msg"     => "Db error: Skill was not updated"
            ));
        }
        
    }

    /**
    * Return skill name from skills table.
    * @return string id and Name.
    */
    public function getSkills() {

      $query = "SELECT `skill_id`, `skill_name`
                FROM `skills`
                ORDER BY `skill_name` ASC";

      $result = $this->db->select($query);

      return $result;
    }

    /**
     * Get all skill details including name, description
     * @return Skill details or null if skill with given id doesn't exist.
     */
    public function getSkill($skill_id) {
        $query = "SELECT *
                    FROM `skills`
                    WHERE `skill_id` = :skill_id";

        $result = $this->db->select($query, array( 'skill_id' => $skill_id ));

        if (count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }

	   /**
	   * Deletes skill from database.
	   * @param int $skill_id of skill being deleted
	   */
	  function deleteSkill($skill_id) {

			$this->db->deleteAll("guide_skills", "skill_id_fk = :skill_id", array( "skill_id" => $skill_id));
			$this->db->delete("skills", "skill_id = :skill_id", array( "skill_id" => $skill_id));

	  }
}
