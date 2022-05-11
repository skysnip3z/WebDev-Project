<?php
require_once('Models/Transit.php');
require_once('Models/UserData/UserData.php');

class TransitContactSearch extends Transit
{
    public function __construct()
    {
        parent::__construct();
    }

    // get active contacts
    public function getContacts($userID)
    {
        $sqlQuery = "SELECT user_id, username FROM users WHERE user_id 
                     IN (SELECT DISTINCT msgs.user_from FROM msgs WHERE msgs.user_to = '$userID')";

        $statement = $this->sql_prep($sqlQuery, true);

        $allContacts = [];

        while($row = $statement->fetch())
        {
            $obj = new UserData($row);
            $allContacts[] = $obj->jsonSerialize();
        }

        return $allContacts;
    }

    // Get the difference between active contacts and non active
    public function getDirectory($a)
    {
        $sqlQuery = "SELECT user_id, username FROM users WHERE NOT user_id = '$a'";

        $statement = $this->sql_prep($sqlQuery, true);

        $allContacts = [];

        while($row = $statement->fetch())
        {
            $obj = new UserData($row);
            $allContacts[] = $obj->jsonSerialize();
        }

        return $allContacts;
    }

    public function getNotifications($a, $b)
    {
        $sqlQuery = "SELECT count(*) AS count FROM msgs WHERE user_to = '$a' AND user_from = '$b' 
                     AND (timestamp > (SELECT last_access FROM conv 
                     WHERE conv.user_a='$a' AND conv.user_b = '$b'))";

        $statement = $this->sql_prep($sqlQuery, true);
        $result = $statement->fetch();
        return $result;
    }
}