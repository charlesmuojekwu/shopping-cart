
<?php 
  require_once("../config.php");
  require_once("../models/database.php");
  require_once("../controllers/class/session.php");
  require_once("../controllers/class/login.php");
  require_once('../partials/header.php');
  if($session->is_signed_in())
  {
    $location = BASE_URL;      
    header("location:$location");
  }
?>

<div class="container">    
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <h3 class="text-center">Login</h3><br>
        <form action="controllers/loginController.php" method="post">
          <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" class="form-control" id="email" name="email" value="user@gmail.com">
          </div>
          <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd" name="password" value="123456">
          </div>
         
          <br>
          <button type="submit" class="btn btn-info" name="login">Submit</button>
        </form>

    </div>
   
    
  </div><br>

</div>




<?php require('../partials/footer.php') ?>
