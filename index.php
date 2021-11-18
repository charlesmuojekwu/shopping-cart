<?php

 require_once("config.php"); 
 require_once("models/database.php");
 require_once("controllers/class/session.php");
 require_once("controllers/class/login.php");
 require_once('partials/header.php');
 $products=$database->query("SELECT * FROM product ORDER by id");

?>
<script>

</script>


<div class="container"> 

  <div class="row">
    <div class="col-sm-12">

    
    <div id="cart-item"></div>
    </div>
   
    
  </div>
  <hr><br>


  <div class="row">
<!-- list products form database -->
    <?php
      if (!empty($products)) 
      { 

        foreach($products as $key=>$product)
        {
    ?>
          <div class="col-sm-3">
            <div class="panel panel-primary">
              <div class="panel-heading"><?= $product['name'] ?> </div>
              <div class="panel-body"><img src="<?= $product['image'] ?>" class="img-responsive products-image" style="width:100%" alt="Image"></div>
              <div class="panel-footer text-center">
              <strong>$<?= $product['amount'] ?></strong>
              <p>
                 <span > (<?php $star = $login->Rating($product["code"]); echo $star;?>) </span>
                <span class="fa fa-star <?php echo ($star > 0) ? 'checked' : '' ;?>"></span>
                <span class="fa fa-star <?php echo ($star > 1) ? 'checked' : '' ;?>"></span>
                <span class="fa fa-star <?php echo ($star > 2) ? 'checked' : '' ;?>"></span>
                <span class="fa fa-star <?php echo ($star > 3) ? 'checked' : '' ;?>"></span>
                <span class="fa fa-star <?php echo ($star > 4) ? 'checked' : '' ;?>"></span>
               

              </p>
              <button class="btn btn-sm btn-info" onclick= "cartAction('add','<?php echo $product["code"] ?>')">Add To Cart</button>
            <hr>

           <!--  product ratings for only logged in users -->
             <?php if($session->is_signed_in()) {
                $userCart = $login->userCart($_SESSION['user_id'],$product["code"]);
                if($userCart)
                {
                  $userRate = $login->userRate($_SESSION['user_id'],$product["code"]);
                  if($userRate)
                  {
              ?>
                     <button class="rated btn btn-primary disabled">Rated</button>
                  <?php 
                  }else{
                  ?>

                 <div class="dropdown rate">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Rate Item
                  <span class="caret"></span></button>
                  <ul class="dropdown-menu text-center">
                    <li><a data-id="1" data-code="<?php echo $product["code"] ?>" class="rating">1 <span class="fa fa-star"></span></a></li>
                    <li><a data-id="2" data-code="<?php echo $product["code"] ?>" class="rating">2 <span class="fa fa-star"></span></a></li>
                    <li><a data-id="3" data-code="<?php echo $product["code"] ?>" class="rating">3 <span class="fa fa-star"></span></a></li>
                    <li><a data-id="4" data-code="<?php echo $product["code"] ?>" class="rating">4 <span class="fa fa-star"></span></a></li>
                    <li><a data-id="5" data-code="<?php echo $product["code"] ?>" class="rating">5 <span class="fa fa-star"></span></a></li>
                  </ul>
                </div>

               
          <?php 
                }

              }

            } ?>
            </div>
            </div>
          </div>
    <?php
        }
      }
    ?>
    
  </div>

</div>

<script type="text/javascript">

  $(document).ready(function () {
  cartAction('','');

  $('.rating').click(function(){
    var rating = $(this).attr('data-id');
    var code = $(this).attr('data-code');
    var name = "rating";
        queryStrings = 'action='+name+'&rate='+ rating+'&code='+ code;
        // post rating
        $.ajax({
        url: "controllers/cartController.php",
        data:queryStrings,
        type: "POST",
        success:function(data){
          $("#cart-item").html(data);
          $('.rate').hide();
          $('.rated').show();
          window.location.reload();
        },
        error:function (){}
        });

  })

  })

  function cartAction(action,product_code) {
    var queryString = "";
    if(action != "") {
      switch(action) {
        case "add":
          queryString = 'action='+action+'&code='+ product_code+'&quantity='+"1";
        break;
        case "remove":
          queryString = 'action='+action+'&code='+ product_code;
        break;
      }  
    }

    jQuery.ajax({
    url: "controllers/cartController.php",
    data:queryString,
    type: "POST",
    success:function(data){
      $("#cart-item").html(data);
    },
    error:function (){}
    });
  }

</script>

<?php require('partials/footer.php') ?>
