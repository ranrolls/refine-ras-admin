<?php

class DB_Functions {

    private $db;

    //put your code here
    // constructor
    function __construct() {
        include_once './db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
     

    /**
     * Get user by email and password
     */
    public function getUserByEmail($token) {
        $result = mysql_query("SELECT * FROM ras_mobile_device_tokens WHERE token= '$token' LIMIT 1");
        return $result;
    }

    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = mysql_query("select * FROM ras_mobile_device_tokens");
        return $result;
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($token) {
        $result = mysql_query("SELECT token from ras_mobile_device_tokens  WHERE token= '$token'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed
            return true;
        } else {
            // user not existed
            return false;
        }
    }

}

?>