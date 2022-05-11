<?php


abstract class Transit
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        require_once('Models/Database.php');

        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    // Executes sql queries to eliminate duplicate code
    public function sql_prep($sqlQuery, $return)
    {
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        if($return == true)
        {
            return $statement;
        }
    }

    // used to clean inputs for security purposes
    public function cleanInput($i)
    {
        $i = trim($i);
        $i = stripcslashes($i);
        $i = htmlspecialchars($i);
        return $i;
    }

    public function fetchUsernameByID($user_id)
    {
        $sqlQuery = "SELECT username FROM users WHERE user_id='$user_id'";

        $statement = $this->sql_prep($sqlQuery, true);
        $row = $statement->fetch();

        $username = $row['username'];

        return $username;
    }
}