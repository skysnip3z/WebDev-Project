<?php
require_once('Models/Transit.php');
/*
 * This class handles errors that arise from user input data
 * from the login and register processes.
 *
 * Superclass of respective login and register classes: share common input errors
 *
 * Subclass of Transit to facilitate db queries - allows subsequent subclasses also.
 *
 * */

abstract class TransitUserError extends Transit
{
    public function __construct()
    {
        parent::__construct();
    }

    // check if email is already in database
    public function checkEmail($email)
    {
        $sqlQuery = "SELECT email FROM users WHERE email='$email'";

        $statement = $this->sql_prep($sqlQuery, true);
        $row = $statement->fetch();

        if($row['email'] == $email)
        {
            return true;
        }
        else{

            return false;
        }
    }
}