<?php
require_once('UserData.php');

class UserDataLogin extends UserData
{
    protected $_email;

    public function __construct($dbRow)
    {
        parent::__construct($dbRow);
        $this->_email= $dbRow['email'];
    }

    public function getEmail()
    {
        return $this->_email;
    }
}