<?php
require_once('TransitPostData.php');

class TransitPostSearch extends TransitPostData
{
     public function __construct()
     {
         parent::__construct();
     }

     // find search string in db subject entries
     public function findWordMatch($str)
     {
         $sqlQuery = "SELECT post_id, poster_id, subject, timestamp FROM (
                      SELECT post_id, poster_id, subject, timestamp FROM posts 
                      where subject like '%$str%'
                      order BY timestamp DESC LIMIT 15) sub ORDER BY timestamp desc;";

         $statement = $this->sql_prep($sqlQuery, true);
         $allPosts = [];

         while($row = $statement->fetch())
         {
             $allPosts[] = new PostDisplay($row);
         }

         return $allPosts;
     }

     // find search string in db subject entries w/category filter
     public function findWordMatchByCategory($subcat, $str)
     {
         $sqlQuery = "SELECT post_id, poster_id, subject, timestamp FROM (
                     SELECT post_id, poster_id, subject, timestamp FROM posts 
                     where (subcategory_id='$subcat' and subject like '%$str%')
                     order BY timestamp DESC LIMIT 15) sub ORDER BY timestamp desc;";

         $statement = $this->sql_prep($sqlQuery, true);
         $allPosts = [];

         while($row = $statement->fetch())
         {
             $allPosts[] = new PostDisplay($row);
         }

         return $allPosts;
     }

     // finds search string in db subject entries, returns JSON friendly format
     public function findLiveWordMatch($str)
     {
         $sqlQuery = "SELECT post_id, subject FROM (
                      SELECT post_id, subject FROM posts 
                      where subject like '$str%' LIMIT 15) sub;";

         $statement = $this->sql_prep($sqlQuery, true);
         $allPosts = [];

         while($row = $statement->fetch())
         {
             $obj = new PostSearch($row);

             // return JSON Serialised
             $allPosts[] = $obj->jsonSerialize();
         }

         return $allPosts;
     }

}