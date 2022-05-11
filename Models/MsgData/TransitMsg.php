<?php
// Required class to be instantiated as a child of Transit
require_once('Models/Transit.php');
require_once('MsgData.php');

class TransitMsg extends Transit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function refreshConv($a, $b, $time)
    {
        $sqlQuery = "SELECT user_from, msg_body, timestamp, img FROM msgs 
                     WHERE ((user_from='$a' AND user_to='$b') OR (user_from='$b' AND user_to='$a'))
                     AND (timestamp > '$time')
                     ORDER BY timestamp";

        $statement = $this->sql_prep($sqlQuery, true);

        $updates = [];

        while($row = $statement->fetch())
        {
            $updates[] = new MsgData($row);
            return $updates;
        }
    }

    public function sendMsg($user_from, $user_to, $msg_body)
    {
        $sqlQuery = "INSERT INTO msgs(user_from, user_to, msg_body, timestamp)
                     VALUES('$user_from','$user_to','$msg_body', now())";

        $this->sql_prep($sqlQuery, false);
    }

    public function getConversation($a, $b)
    {
        $sqlQuery = "SELECT user_from, msg_body, timestamp, img FROM msgs 
                     WHERE (user_from='$a' AND user_to='$b') OR (user_from='$b' AND user_to='$a')
                     ORDER BY timestamp";

        $statement = $this->sql_prep($sqlQuery, true);

        $allConvs = [];

        while($row = $statement->fetch())
        {
            $allConvs[] = new MsgData($row);
        }

        return $allConvs;
    }

    public function setConversation($from, $to, $aware)
    {
        if($aware === true){
            $sqlQuery = "INSERT INTO conv(user_a, user_b, last_access) VALUES('$from','$to', now())";
            $this->sql_prep($sqlQuery, false);
        }else{
            $sqlQuery = "INSERT INTO conv(user_a, user_b, last_access) 
                         VALUES('$from','$to', '2010-01-01 00:00:00')";
            $this->sql_prep($sqlQuery, false);
        }
    }

    public function startConversation($a, $b)
    {
        $sqlQuery = "INSERT INTO msgs(user_from, user_to, msg_body, timestamp)
                     VALUES('$a','$b','SYSTEM GENERATED: Conversation Started', now())";
        $this->sql_prep($sqlQuery, false);
    }

    public function startTyping($a)
    {
        $sqlQuery = "UPDATE users SET typing = '1' WHERE user_id = '$a'";
        $this->sql_prep($sqlQuery, false);
    }

    public function stopTyping($a)
    {
        $sqlQuery = "UPDATE users SET typing = '0' WHERE user_id = '$a'";
        $this->sql_prep($sqlQuery, false);
    }

    public function checkTyping($b)
    {
        $sqlQuery = "SELECT typing FROM users WHERE user_id = '$b'";
        $statement = $this->sql_prep($sqlQuery, true);
        $result = $statement->fetch();

        return $result;
    }

    public function updateLastAccess($a, $b)
    {
        $sqlQuery = "UPDATE conv SET last_access = now() WHERE user_a = '$a' AND user_b = '$b'";
        $this->sql_prep($sqlQuery, false);
    }
}