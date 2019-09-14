<?php

/**
 * OuzelGuides - Ouzel Outfitters Guide Portal
 *
 * @author Will Sharp
 * @link   http://www.oregonrafting.com
 */

/**
 * Notes class.
 */
class Note {

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
     * Inserts user note into database.
     * @param int $visitor_id of user who is posting the comment.
     * @param string $note Note text.
     * @return string JSON encoded string that consist of 3 fields:
     * visitor,note and postTime
     */
    public function insertUserNote($user_id, $visitor_id, $note, $public) {
        $user     = new ASUser($visitor_id);
        $userInfo = $user->getInfo();
        $datetime = date("Y-m-d H:i:s");

        $err = $this->db->insert("user_notes",  array(
            "user_id_fk"     => $user_id,
            "created_by"     => $visitor_id,
            "public"         => $public,
            "posted_by_name" => $userInfo['username'],
            "user_note"      => strip_tags($note),
            "created_on"     => $datetime
        ));

        if ($err) {
          echo json_encode(array(
            "status"        => "success",
            "msg"           => "Success! Note was added successfully",
            "usernotes_id"  => $this->db->lastInsertId(),
            "visitor"       => $userInfo['username'],
            "is_public"     => $public,
            "user_note"     => stripslashes( strip_tags($note) ),
            "created_on"    => $datetime
          ));
        } else {
          echo json_encode(array(
            "status"  => "failure",
            "msg"     => "Db error: Note was not added"
          ));
        }

    }

    /**
     * Return all comments left by one user.
     * @param int $userId Id of user.
     * @return array Array of all user's comments.
     */
    public function getUserComments($userId) {
        $result = $this->db->select(
                    "SELECT * FROM `as_comments` WHERE `user_id` = :id",
                    array ("id" => $userId)
                  );

        return $result;
    }

    /**
     * Return all notes posted for a user.
     * @param int $userId Id of user.
     * @return array Array of all user's notes.
     */
    public function getUserNotes($userId, $is_admin) {
        if ($is_admin) {
            $result = $this->db->select(
                "SELECT * FROM `user_notes` WHERE `user_id_fk` = :id ORDER BY `created_on` DESC",
                array ("id" => $userId)
            );
        } else {
            $result = $this->db->select(
                "SELECT * FROM `user_notes` WHERE `user_id_fk` = :id AND `public` = 'Y' ORDER BY `created_on` DESC",
                array ("id" => $userId)
            );
        }

        return $result;
    }

    /**
     * Delete a user note.
     * @param int $usernotesId Id of a user note.
     * @return array Array of all user's notes.
     */
    public function deleteUserNote($note_id) {
        $result = $this->db->delete('user_notes','usernotes_id = :note_id', array( "note_id" => $note_id ));

        return $result;
    }


    /**
     * Return comments from database.
     * @param int $limit Required number of comments.
     * @return array Array of comments.
     */
    public function getComments() {
        return $this->db->select("SELECT * FROM `as_comments` WHERE YEAR(`post_time`) = YEAR(CURRENT_TIMESTAMP) ORDER BY `post_time` DESC");
    }
}
