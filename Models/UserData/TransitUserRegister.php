<?php
require_once ('TransitUserError.php');


class TransitUserRegister extends TransitUserError
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register($username, $password, $email)
    {
           $sqlQuery = "INSERT INTO users (user_id, username, password, email)
                        VALUES (NULL,'$username','$password','$email')";

            $this->sql_prep($sqlQuery, false);
    }
}