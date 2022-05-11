<?php

require_once('Models/PostData/PostSearch.php');
/*
 * Class designed as a medium container for all data related to posts
 * */
class PostDisplay extends PostSearch
{
    // Attributes correspond to their db counterparts
    protected $_poster_id, $_timestamp;

    public function __construct($dbRow)
    {
        parent::__construct($dbRow);
        $this->_poster_id = $dbRow['poster_id'];
        $this->_timestamp = $dbRow['timestamp'];
    }

    /*
     * All getter methods below for principles of encapsulation
     * */
    public function getPosterID()
    {
        return $this->_poster_id;
    }
    public function getTimestamp()
    {
        return $this->_timestamp;
    }

}