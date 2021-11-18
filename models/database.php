<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "abc_ecommerce";
    private $conn;
   
    
    function __construct() {
        
        $this->open_db_conn();
        
    }
    
    public function open_db_conn() {
        
        $this->conn = new mysqli($this->host,$this->user,$this->password,$this->database);
        
        if($this->conn->connect_errno) {
            die("Database connection failed" . $this->conn->connect_error);
        }

    }
    
    public function query($sql) {
        
        $result = $this->conn->query($sql);
        $this->confirm_query($result); 
        $the_object_array = array();
        
        while($row = mysqli_fetch_array($result)) {
            
            $the_object_array[] = $row;
            
        }
        
        return $the_object_array;
        
    }

    public function insert($sql) {
        
        $result = $this->conn->query($sql);
        $this->confirm_query($result); 
        
        return $result;
        
    }
    
    private function confirm_query($result) {
        if(!$result) {
            die("Query failed!" . $this->conn->error);
        }
    }
    
    public function escape($string) {
        
        $escaped_string = $this->conn->real_escape_string($string);
        return $escaped_string;
    }
    

    
}

$database = new Database();

?>
