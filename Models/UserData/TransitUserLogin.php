<?php
require_once('Models/UserData/TransitUserError.php');


class TransitUserLogin extends TransitUserError
{

    public function __construct()
    {
        parent::__construct();
    }

    public function fetchUserByEmail($email)
    {
            $sqlQuery = "SELECT user_id,username,email FROM users WHERE email='$email'";

            $statement = $this->sql_prep($sqlQuery, true);
            $row = $statement->fetch();

            return $row;
    }

    // Authenticate user login
    public function authenticate($e, $p)
    {

        $sqlQuery = "SELECT password FROM users WHERE email = '$e'";

        // Private function is called to execute sql query
        $statement = $this->sql_prep($sqlQuery, true);
        // Retrieve the hash of the password in db in a attribute=>value array
        $db_arr = $statement->fetch();
        // Retrieve the value of the password
        $db_hash = $db_arr['password'];

        // verify user input password against hash
        if(password_verify($p, $db_hash))
        {
            return true;
        }
        else {
            return false;
        }
    }
}