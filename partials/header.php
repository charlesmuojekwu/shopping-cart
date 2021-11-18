
<!DOCTYPE html>
<html lang="en">
<head>
  <title>E-COMMERCE</title>
  <base href="<?= BASE_URL ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */ 
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
    
    /* Remove the jumbotron's default bottom margin */ 
     .jumbotron {
      margin-bottom: 0;
    }

    /* products height */
    .products-image{
      max-height: 300px;
    }

    /*color for ratings*/
    .checked {
      color: orange;
    }

    /* styling input for counter */
    .counter,.border{
      border: none;
      width: 60px;
      text-align: center;
    }


    /* align table colunm name to center */
    table th,tr{
      text-align: center;
    }
  


     
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }


  </style>
</head>
<body>

<div class="jumbotron">
  <div class="container text-center">
    <h1>Online Store</h1>      
  </div>
</div>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Store</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
         <?php if($session->is_signed_in()) :
          $user_info = $login->user_details($_SESSION['user_id']);
          ?>

        <li><a href="#" style="color:#fff"> Welcome : 
          <?php
           echo $user_info['email']; 
          ?>
        </li></a>
        <li><a href="#"  style="color:#fff"> Wallet Balance : 
          <?php
           echo $user_info['wallet']; 
          ?> USD
        </li></a>
        <?php endif; ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php if($session->is_signed_in()) : ?>
          <li><a href="controllers/loginController.php?logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li> 
        <?php else: ?>
           <li><a href="views/login.php"><span class="glyphicon glyphicon-user"></span> Login</a></li>
           <li><a href="views/register.php"><span class="glyphicon glyphicon-edit"></span> Register</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<p><?php echo $session->message ?></p>