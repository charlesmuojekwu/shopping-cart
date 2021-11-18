
<?php 
  require_once("../config.php");
  require_once("../controllers/class/session.php");
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
      <h3 class="text-center">Register</h3><br>
        <form action="controllers/loginController.php" method="post">
          <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd" name="password" required>
          </div>
           <div class="form-group">
            <label for="pwd"> Confirm Password:</label>
            <input type="password" class="form-control" id="pwd" name="c-password" required>
          </div>
          <div class="form-group">
            <label for="pwd"> Fund Wallet</label>
            <select class="form-control" id="pwd" name="wallet" required>
              <option value="100">100 USD</option>
              <option value="200">200 USD</option>
              <option value="300">300 USD</option>
              <option value="400">400 USD</option>
              <option value="500">500 USD</option>
            </select>
          </div>
         
          <br>
          <button type="submit" class="btn btn-info" name="register">Submit</button>
        </form>

    </div>
   
    
  </div><br>

</div>




<?php require('../partials/footer.php') ?>
