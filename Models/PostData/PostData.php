<?php
// Required class for PostData to be instantiated as a child of PostDisplay
require_once('Models/PostData/PostDisplay.php');
/*
 * Class designed as a large container for all data related to posts
 * */
class PostData extends PostDisplay
{
    // Attributes correspond to their db counterparts
    protected $_subcategory_id, $_parent_id, $_post_body, $_img;

    public function __construct($dbRow)
    {
        // Constructor calls on parent constructor for inheritance
        parent::__construct($dbRow);
        $this->_subcategory_id = $dbRow['subcategory_id'];
        $this->_parent_id = $dbRow['parent_id'];
        $this->_post_body = $dbRow['post_body'];
        $this->_img = $dbRow['img'];
    }

    /*
      * All getter methods below for principles of encapsulation
      * */
    public function getSubcategoryID()
    {
        return $this->_subcategory_id;
    }

    public function getParentID()
    {
        return $this->_parent_id;
    }

    public function getPostBody()
    {
        return $this->_post_body;
    }

    public function getImg()
    {
        return $this->_img;
    }
}