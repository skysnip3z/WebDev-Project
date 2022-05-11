<?php
require_once('Models/Transit.php');
require_once('Models/PostData/PostData.php');
require_once('Models/PostData/PostDisplay.php');

class TransitWatchList extends Transit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addToWatchList($user_id, $post_id)
    {
        $sqlQuery = "insert into watchlist(user_id, post_id) 
                     values($user_id, $post_id)";

        $this->sql_prep($sqlQuery, false);
    }

    public function fetchWatchList($userID)
    {
        $sqlQuery = "select post_id, poster_id, subject, timestamp from posts 
                    where post_id in (select post_id from watchlist where user_id='$userID')";

        $statement = $this->sql_prep($sqlQuery, true);
        $allPosts = [];

        while($row = $statement->fetch())
        {
            $allPosts[] = new PostDisplay($row);
        }

        return $allPosts;
    }
}