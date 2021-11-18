<?php
require_once("../config.php"); 
require_once("../models/database.php");
require_once("class/cart.php");

// check cart action has value
if(!empty($_POST["action"])) {
	switch($_POST["action"]) {
		case "add": // add item to cart
			$cart->addCart($_POST["quantity"]);
		break;
		case "remove": // remove item from cart
			$cart->removeCart();
		break;
		case "updateAmt": // update quantity of cart item 
			$cart->updateCart($_POST["code"],$_POST["quantity"]);
		break;
		case "cartPayment": // add cart item to database
			if(isset($_SESSION['user_id'])){
				$shipping = $_POST["shipping"];
				$get_payment=$cart->cartPayment($shipping,$_SESSION['user_id']);
			}else{
				$Auth_login="Sign in to continue to process Payment!";
			}
		break;
		case "rating": // insert users rating 
			$cart->rateProduct($_SESSION['user_id'],$_POST["code"],$_POST["rate"]);
		break;						
	}
}


?>

<?php 
			// if payment process successful it shows this
	 		if(isset($get_payment))
		    { 
		    	if(!empty($get_payment)){

		    	?>
			    	<div class="col-md-2-offset col-md-8">
				    	<div class="alert alert-success alert-dismissible">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close" onClick="window.location.reload();">&times;</a>
						  <strong>Success!</strong>
						  
						  <p>PREVIOUS ACCOUNT BALANCE : <?php echo $get_payment['wallet'] ?> USD</p>
						  <p>TOTAL PURCHASE COST (Including Shipping) : <?php echo $get_payment['total'] ?> USD</p>
						  <p>NEW ACCOUNT BALANCE : <?php echo $get_payment['balance'] ?> USD</p>
						  <p></p>
						</div>
					</div>
    <?php  		}else{  ?>
		
					<div class="alert alert-danger alert-dismissible">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>Inssuficient Balance!</strong> Payment could not be completed </a>
					</div>
    <?php  		} 

    		}
     ?>
		



<?php
// CHECK IF SESSION HAS CART ITEMS
if(isset($_SESSION["cart_item"]))
{
    $item_total = 0;
   
?>	



 <table class="table table-striped table-hover text-center">
 	<?php 
 		// CHECK IF USER IS LOGGED IN
	 		if(isset($Auth_login))
		    { ?>
		    	<div class="alert alert-danger alert-dismissible">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <strong><?php echo $Auth_login ?></strong> <a href="views/login.php">Login Here</a>
				</div>
    <?php  }  ?>
		
 	
    <thead>
      <tr>
        <th>Product</th>
      
        <th>Quantity</th>
        <th>Price </th>
        <th>Total </th>
        <th>Remove </th>
      </tr>
    </thead>
     <tbody>

  
<?php	
	/// DISPLAY ITEMS IN THE CART
    foreach ($_SESSION["cart_item"] as $index=>$item){
		?>
				
				
				<tr>
				<td><strong> <input class='border' type="text" value='<?php echo $item["name"]; ?>' readonly />  </strong></td>
				<!-- <td></td> -->
				<td>
					 <button class='down_count btn btn-info' data-id='<?php echo $index; ?>'  title='Down'><i class='fa fa-minus'></i></button>
			          <input class='counter count_<?php echo $index; ?>' type="text" value='<?php echo $item["quantity"]; ?>'  readonly />    
			          <button class='up_count btn btn-info' data-id='<?php echo $index; ?>' title='Up'><i class='fa fa-plus'></i></button>
				</td>
				<td > <input class='border amount_<?php echo $index; ?>' type="text"  value='<?php echo "$".$item["amount"]; ?>' readonly /></td>
				<td > <input class='border qtySum_<?php echo $index; ?>' type="text"  value='$<?php echo $item["amount"]*$item["quantity"]; ?>' readonly /> </td>
				<td><a onClick="cartAction('remove','<?php echo $item["code"]; ?>')" class="btnRemoveAction cart-action"><i class="fa fa-trash"></i></a></td>
				</tr>
		<?php
        	$item_total += ($item["amount"]*$item["quantity"]);
		}
		?>



	<tr>
        <td></td>
        <td></td>
       <!--  <td></td> -->
        <td rowspan="2"><strong>TOTAL (USD)</strong></td>
        <td><strong>  <input class='border totalSum' type="text"  value='<?php echo $item_total; ?>' readonly /></strong></td>
        <td></td>
      </tr>

     



</tbody>
	
</table>
	<div class="pull-right" style="display: none;" id="payable">
     	<p> Shipping Cost : <span id="shipCost"></span></p>
     	<p>  <strong>TOTAL PAYABLE : <span id="amountPay"></span></strong> </p>

     </div>
<p>
      <select id="shipping" class="custom-select form-control" required>
        <option selected value="">Choose Shipping Option</option>
        <option value="0">Pick Up (USD 0)</option>
        <option value="5">UPS (USD 5)</option>
      </select>

    </p>

  <p class="pull-right"><button class="btn btn-primary btn-md" id="cartPayment" >Pay</button></p>

  </form> 

<script type="text/javascript">
  $(document).ready(function(){

//DECREMENT COUNT FOR QUANTITY
    $('.down_count').click(function() {
		var code =$(this).attr('data-id');
    	var qty = $('.count_'+code).val();
    	var newQty = (qty) - 1;
    	newQty = newQty < 1 ? 1 : newQty;
    	$('.count_'+code).val(newQty);
    	var name = "updateAmt";
    	queryStrings = 'action='+name+'&code='+ code+'&quantity='+newQty;
    	updateAmount (queryStrings);
    	
    });

    
//INCREMENT COUNT FOR QUANTITY
    $('.up_count').click(function() {   	
    	var code =$(this).attr('data-id');
    	var qty = $('.count_'+code).val()
    	var newQty = parseInt(qty,10) + 1;
    	newQty = newQty < 1 ? 1 : newQty;
    	$('.count_'+code).val(newQty);
    	var name = "updateAmt";
    	queryStrings = 'action='+name+'&code='+ code+'&quantity='+newQty;
    	updateAmount (queryStrings);
    });


// UPDATE QUANTITY IN CART
    function updateAmount(query) {
    	$.ajax({
	    url: "controllers/cartController.php",
	    data:query,
	    type: "POST",
	    success:function(data){
	    	$("#cart-item").html(data);
	    },
	    error:function (){}
	    });
	 }


/// CHECK FOR SHIPPING COST
	$('#shipping').change(function(){
		if($('#shipping').val() != ''){
			$('#payable').show();
			var shipping_cost = $('#shipping').val()
			var total = $('.totalSum').val()
			$('#shipCost').html(shipping_cost +' USD ');
			var amt = Number(total) + Number(shipping_cost); 
			$('#amountPay').html(amt + ' USD ');
		}else{
			$('#payable').hide();
		}
	})

// SUBMIT CART TO DB
	   $('#cartPayment').click(function() {   

	  	var shipping = $('#shipping').val();
	  	if(shipping==''){
	  		alert('Please select a shiping method');
	  	}else{
		  	var name = "cartPayment";
		  	queryStrings = 'action='+name+'&shipping='+ shipping;

	    	$.ajax({
		    url: "controllers/cartController.php",
		    data:queryStrings,
		    type: "POST",
		    success:function(data){
		    	$("#cart-item").html(data);
		    },
		    error:function (){}
		    });
		}
	 });


	





});

</script>

  <?php
}
?>