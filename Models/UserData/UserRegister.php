<?php


class UserRegister
{
    protected $_user_r, $_email1, $_email2, $_password1, $_password2,
              $_username, $_error;

    public function __construct($email1, $email2, $password1, $password2, $username)
    {
        require_once("TransitUserRegister.php");
        $this->_user_r = new TransitUserRegister();

        $this->_email1 = $this->_user_r->cleanInput($email1);
        $this->_email2 = $this->_user_r->cleanInput($email2);
        $this->_password1 = $this->_user_r->cleanInput($password1);
        $this->_password2 = $this->_user_r->cleanInput($password2);
        $this->_username = $this->_user_r->cleanInput($username);

        $this->_error = null;
    }

    public function signup()
    {
        if($this->_user_r->checkEmail($this->_email1))
        {
            $this->_error = "err_email_exists";
        }
        else if(!(filter_var($this->_email1, FILTER_VALIDATE_EMAIL)))
        {
            $this->_error = "err_email_format";
        }
        else if($this->checkVarMatch($this->_email1, $this->_email2) == false)
        {
            $this->_error = "err_email_mismatch";
        }
        else if($this->checkVarMatch($this->_password1, $this->_password2) == false)
        {
            $this->_error = "err_password_mismatch";
        }


        if($this->_error != null)
        {
            return $this->_error;
        }
        else{
            $this->signup_commit();
        }
    }

    private function signup_commit()
    {
        $password_hash = password_hash($this->_password1, PASSWORD_BCRYPT, ["cost" => 11]);
        $password1 = null;
        $password2 = null;

        $this->_user_r->register("$this->_username", "$password_hash",
            "$this->_email1");
    }

    private function checkVarMatch($i1, $i2)
    {
        if($i1 == $i2)
        {
            return true;
        }
        else{
            return false;
        }
    }

}