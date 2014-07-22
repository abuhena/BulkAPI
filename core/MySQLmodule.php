<?php
/**
 * Created by PhpStorm.
 * User: shaikot
 * Date: 7/10/14
 * Time: 10:59 PM
 */

class MySQLhelper {

    public $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        if($this->mysqli->connect_error)
        {
            throw new Exception('MySQLi connection failed with error number: '.$this->mysqli->connect_errno.' Please check your configuration file.');
        }else{
            return true;
        }
    }
}