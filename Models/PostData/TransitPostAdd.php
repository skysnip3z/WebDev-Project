<?php
// Required class to be instantiated as a child of Transit
require_once('Models/Transit.php');
/*
 * Class designed to handle all operation that concern adding into the db
 * A single method retrieves, added for the purpose of transiently achieving this
 * */
class TransitPostAdd extends Transit
{
    public function __construct()
    {
        // Call to parent constructor, for inheritance
        parent::__construct();
    }

    /*
     * Function: handles a prepared statement to record posts into the db
     */
    public function createPost($poster_id, $subcat_id,$subject,$post_body,$img)
    {

        $sqlQuery = "insert into posts 
                     (poster_id, subcategory_id, subject, post_body, timestamp, img) 
                      values('$poster_id', '$subcat_id', '$subject', '$post_body', now(), '$img')";

        $this->sql_prep($sqlQuery, false);
        //Return flag is false as we only need to execute the query
    }

    /*
     * Function: This function utilises a prepared sql statement
     * to get a user's user_id from the db through a username parameter
     */
    public function getUserIDByUsername($username)
    {
        $sqlQuery = "SELECT user_id FROM users WHERE username='$username'";

        $statement = $this->sql_prep($sqlQuery, true);
        $row = $statement->fetch();
        $user_id = $row['user_id']; // Extracting value from subsequent query array

        return $user_id; // Returning value to caller
    }

    /*
     * Function: This function utilises a prepared sql statement
     * to get a post's subcategory_id through it's ID
     */
    public function getSubcatIDByPostID($post_id)
    {
        $sqlQuery = "SELECT subcategory_id FROM posts WHERE post_id='$post_id'";

        $statement = $this->sql_prep($sqlQuery, true);
        $row = $statement->fetch();
        $subcat_id = $row['subcategory_id']; // Extracting value from subsequent query array

        return $subcat_id; // Returning value to caller
    }

    /*
    * Function: This function utilises a prepared sql statement
    * to get data from a reply to a post and commit it to the db
    */
    public function replyToPost($poster_id, $subcategory_id, $parent_id, $post_body)
    {
        // In lieu of converting time to sql format from php, i have used "now()"
        // in the statement for added efficiency.
        $sqlQuery = "INSERT INTO posts(poster_id, subcategory_id, parent_id, post_body, timestamp) 
                     VALUES('$poster_id', '$subcategory_id','$parent_id','$post_body', now())";

        $this->sql_prep($sqlQuery, false);
        //Return flag is false as we only need to execute the query
    }
}