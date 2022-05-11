<?php
require_once('Models/Transit.php');
require_once('Models/PostData/PostData.php');
require_once('Models/PostData/PostDisplay.php');
require_once('Models/PostData/PostSearch.php');

class TransitPostData extends Transit
{
    public function __construct()
    {
        parent::__construct();
    }



    public function getAllReplies($parentID)
    {
        $sqlQuery = "SELECT * FROM posts WHERE posts.post_id = NULL 
                     UNION 
                     SELECT * FROM posts WHERE posts.parent_id = $parentID 
                     ORDER BY timestamp ASC";

        $statement = $this->sql_prep($sqlQuery, true);
        $replies = [];

        while($row = $statement->fetch())
        {
            $replies[] = new PostData($row);
        }
            return $replies;
    }

    public function getAllParentDisplays($subcategory_id)
    {
        $sqlQuery = "SELECT post_id, poster_id, subject, timestamp FROM (
                     SELECT post_id, poster_id, subject, timestamp FROM posts 
                     where (subcategory_id = '$subcategory_id' AND parent_id IS NULL) 
                     order BY timestamp DESC LIMIT 15) sub ORDER BY timestamp desc;";

        $statement = $this->sql_prep($sqlQuery, true);
        $allPosts = [];

        while($row = $statement->fetch())
        {
            $allPosts[] = new PostDisplay($row);
        }

        return $allPosts;
    }

    public function fetchPostByID($post_id)
    {
        $sqlQuery = "SELECT * FROM posts WHERE post_id='$post_id'";

        $statement = $this->sql_prep($sqlQuery, true);
        $row = $statement->fetch();

        return $row;
    }

    // in use within view
    public function fetchTimestampByID($post_id)
    {
        $sqlQuery = "SELECT timestamp FROM posts WHERE post_id='$post_id'";

        $statement = $this->sql_prep($sqlQuery, true);
        $row = $statement->fetch();
        $timestamp = $row['timestamp'];

        $post_time = date("d/m/y g:i A", strtotime($timestamp));

        return $post_time;
    }

}