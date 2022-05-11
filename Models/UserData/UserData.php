<?php


class UserData implements JsonSerializable
{
    protected $_user_id, $_username;

    public function __construct($dbRow)
    {
        $this->_user_id = $dbRow['user_id'];
        $this->_username = $dbRow['username'];
    }

    public function getUserID()
    {
        return $this->_user_id;
    }

    public function getUsername()
    {
        return $this->_username;
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
        return["userID" => $this->_user_id, "username" => $this->_username];
    }
}