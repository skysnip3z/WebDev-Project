<?php


class MsgData implements JsonSerializable
{
    protected $_user_from, $_msg_body, $_timestamp, $_img, $_isImage;

    public function __construct($dbRow)
    {
        $this->_user_from = $dbRow['user_from'];

        if(is_null($dbRow['img']))
        {
            $this->_msg_body = $dbRow['msg_body'];
            $this->_isImage = 0;
        }else{
            $this->_img = $dbRow['img'];
            $this->_isImage = 1;
        }
        $timestamp = $dbRow['timestamp'];

        $this->_timestamp = date("H:i:s d/m/y", strtotime($timestamp));
    }

    public function getUserFrom()
    {
        return $this->_user_from;
    }

    public function getUserTo()
    {
        return $this->_user_to;
    }

    public function getMsgBody()
    {
        return $this->_msg_body;
    }

    public function getTimestamp()
    {
        return $this->_timestamp;
    }

    public function getImg()
    {
        return $this->_img;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        if($this->_isImage == 0)
        {
            return["u" => $this->_user_from, "mb" => $this->_msg_body,
                   "ts" => $this->_timestamp, "isI" => $this->_isImage];
        }else{
            return ["u" => $this->_user_from, "ts" => $this->_timestamp,
                    "i" => $this->_img, "isI" => $this->_isImage];
        }
    }
}