<?php
/*
 * Class designed as minimum container for all data related to posts
 * */

class PostSearch implements JsonSerializable
{
    // Attributes correspond to their db counterparts
    protected $_post_id, $_subject;

    public function __construct($dbRow)
    {
        $this->_post_id = $dbRow['post_id'];
        $this->_subject = $dbRow['subject'];
    }

    /*
      * All getter methods below for principles of encapsulation
      * */
    public function getPostID()
    {
        return $this->_post_id;
    }

    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return["postID" => $this->_post_id, "subject" => $this->_subject];
    }
}