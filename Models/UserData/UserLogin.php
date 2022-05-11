<?php

/*
 * Class used to handle any user functions that do not require direct db access
 *
 * Only transient access to db is permitted in this class for greater security.
 *
 * */

class UserLogin
{
    protected $_user_ul;

    public function __construct()
    {
        require_once("TransitUserLogin.php");
        require_once("UserDataLogin.php");
        $this->_user_ul = new TransitUserLogin();
    }

    // Used to log a user in
    public function login($e, $p)
    {
        // sanitised $e is stored as it is used multiple time
        $email_clean = $this->_user_ul->cleanInput($e);

        // checks if email is a valid email from db
        $email_checked = $this->_user_ul->checkEmail($email_clean);

        // As long as no email input errors occur
        if($email_checked == true)
        {
            // If email exists in db, validate password against it
            if ($this->_user_ul->authenticate($email_clean, $this->_user_ul->cleanInput($p)))
            {
                // A new user data object is created for use in creating a session
                $user_d = new UserDataLogin($this->_user_ul->fetchUserByEmail($email_clean));

                // UserDataLogin object is returned
                return $user_d;
            }
            else{
                //email error = false THEN password error must be true SO login = false
                 return "err_password";
            }
        }
        else{
            // email error = true THEN login = false
           return "err_email";
        }
    }

}