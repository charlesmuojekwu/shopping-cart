<?php 
  require_once("../config.php");
  require_once("../models/database.php");
  require_once("../controllers/class/login.php");
  require_once("../controllers/class/session.php");

  if(isset($_POST['login']))
  {
      $email = trim($_POST['email']);
      $password = $database->escape(trim($_POST['password']));

      $user_found = $login->verify_user($email);

      if($user_found) 
      {

          if(password_verify($password,$user_found['password'])) 
          {
            
            $session->login($user_found); 
            $location = BASE_URL;      
            header("location:$location");

             $session->message("<div class='alert alert-success text-center' role='alert'>Welcome to Online Store. </div>");
                
          } 
          else 
          {
                
              $session->message("<div class='alert alert-warning text-center' role='alert'>Incorrect credentials!</div>");
              $location = BASE_URL.'views/login.php';
              header("location:$location");
          }
        
      } 
      else 
      {
          $session->message("<div class='alert alert-warning text-center' role='alert'>Email not found!</div>");
          $location = BASE_URL.'views/login.php';
          header("location:$location");
      }

  }

  if(isset($_POST['register'])) {
  
             $email = strtolower(trim($_POST['email']));
             $password = trim($_POST['password']);
             $confirm_password = trim($_POST['c-password']);
             $wallet = trim($_POST['wallet']);


             $error = '';
             $check_email = $login->verify_user($email);
             if($check_email){
             $error.='Email Already Exists';
             }

             if($password !== $confirm_password){
              $error.='<br> Password Mismatch';
             }


            if($error!=''){
              $session->message("<div class='alert alert-warning text-center' role='alert'>".$error."</div>");
              $location = BASE_URL.'views/register.php';
              header("location:$location");
            }else{
              $register_user = $login->register_user($email, $password, $wallet);
        
              if($register_user == true) {

                $session->message("<div class='alert alert-success text-center ' role='alert'>Your account was registered, you can Log in now!</div>");
                $location = BASE_URL.'views/login.php';
                header("location:$location");
              }
            }



  }


  if(isset($_GET['logout'])) {
    $session->logout();
    $location = BASE_URL;      
    header("location:$location");
  }


?>