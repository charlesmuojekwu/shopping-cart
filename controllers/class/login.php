<?php

class Login {


    // verify users email
	public static function verify_user($email) 
	{
        
        global $database;
        
        // verify user login

        $email = $database->escape($email);
        
        $sql = "SELECT * FROM users WHERE ";
        $sql .= "email = '{$email}' ";
        $sql .= "LIMIT 1";
        $the_result_array = $database->query($sql);
        
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
        
    }


    // get looged in users details
    public static function user_details($userId) 
    {
        
        global $database;
        
        // Get user details 

        $userId = $database->escape($userId);
        
        $sql = "SELECT * FROM users WHERE ";
        $sql .= "id = '{$userId}' ";
        $sql .= "LIMIT 1";
        $the_result_array = $database->query($sql);
        
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
        
    }


    /// register new users
     public static function register_user( $email, $password, $wallet) {
        
        global $database;
        
        // register user

        
        $email = $database->escape($email);
        $password = $database->escape($password);
        $wallet = $database->escape($wallet);
        
        
        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        
        $sql = "INSERT INTO users (email, password, wallet) ";
        $sql .= "VALUES ('{$email}','{$password}', '{$wallet}')";
        
        $result = $database->insert($sql);
        
        if(!$result) {
            return false;
        } else {
            return true;
            
        }
        
    }


    // check if users has purchased product
    public static function userCart($userId,$code) 
    {
        
        global $database;
        
        // verify user login

        $email = $database->escape($userId);
        
        $sql = "SELECT * FROM cart WHERE ";
        $sql .= "user_id = '{$userId}' ";
        $sql .= "AND code = '{$code}' ";
        $sql .= "LIMIT 1";
        $result = $database->query($sql);
        
        if(!$result) {
            return false;
        } else {
            return true;
            
        }
        
    }


    // check if users has rated product
    public static function userRate($userId,$code) 
    {
        
        global $database;
        
        // verify user login

        $email = $database->escape($userId);
        
        $sql = "SELECT * FROM ratings WHERE ";
        $sql .= "user_id = '{$userId}' ";
        $sql .= "AND product_code = '{$code}' ";
        $sql .= "LIMIT 1";
        $result = $database->query($sql);
        
        if(!$result) {
            return false;
        } else {
            return true;
            
        }
        
    }


    // get average product rating
    public static function Rating($code) 
    {
        
        global $database;
        
        // verify user login

        $email = $database->escape($code);

        $sql = "SELECT * FROM ratings WHERE product_code = '$code'";
        $result = $database->insert($sql);
        
        $result_num = mysqli_num_rows($result);
      
        
        $max = 0;
        foreach ($result as $rate => $count) { // iterate through array
        $max = $max+$count['rating'];
        }

        if($result_num > 0){
            $averageRating = @($max / $result_num);
        }

        
        
        if(isset($averageRating)) {
             return $averageRating;
        } else {
            return 0;
        }
        
    }

} 

$login = new Login();




?>