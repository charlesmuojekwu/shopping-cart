<?php

require_once("../config.php"); 
require_once("../models/database.php");
require_once("login.php");


class Cart extends Login {

	function  __construct(){
		session_start();
	}

	public function addCart($quantity) {
		global $database;

		if(!empty($quantity)) {
				// fetch cart list from database
				$productByCode = $database->query("SELECT * FROM product WHERE code='" . $_POST["code"] . "'");
				$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$quantity, 'amount'=>$productByCode[0]["amount"]));
				
				if(!empty($_SESSION["cart_item"])) {
					//add cart to other list of cart
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}else{
					// show items in cart
					$_SESSION["cart_item"]=$itemArray;
				}
			return 	$_SESSION["cart_item"];

			}else{
				return 	$_SESSION["cart_item"] = '';
			}
	}


	/// Remove item from cart
	public function removeCart() {
		global $database;

		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_POST["code"] == $k)
						unset($_SESSION["cart_item"][$k]);
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	}

	/// Cart item payment
	public function cartPayment($shipping,$userId) {
		global $database;

		//fetch users wallet
		$user = $this->user_details($userId);
		$wallet = $user['wallet'];

		

		// get total amount for the cart items
		$item_total = 0;
		foreach ($_SESSION["cart_item"] as $key => $value)
		{
			$item_total += ($value["amount"]*$value["quantity"]);
		}

		// add shipping charge to product
		$total_cost = $shipping + $item_total;

		
		//store cart payment
		$payment = [];

		if($total_cost <= $wallet)
		{	
			
			 //get new wallet balance
			 $new_balance = $wallet - $total_cost;

			 $database->insert("UPDATE users set wallet='$new_balance' WHERE id='$userId'");
		


			// add cart items to database
			foreach ($_SESSION["cart_item"] as $key => $value) 
			{
				$quantity = $value['quantity'];
				$name = $value['name'];
				$amount = $value['amount']*$value['quantity'];
				$code = $value['code'];
				$user_id = $_SESSION['user_id'];

				$database->insert("INSERT INTO `cart`( `user_id`, `product`, `qty`, `amount`, `code`) VALUES ('$user_id','$name','$quantity','$amount','$code')");

			}


			$payment=['wallet'=>$wallet,'total'=>$total_cost,'balance'=>$new_balance];

			$this->emptyCart();
		}


		return $payment;
	}


	/// Update items in cart
	public function updateCart($code,$quantity) {
		$_SESSION["cart_item"][$code]["quantity"] = $quantity;
	}


	/// Empty cart
	public function emptyCart() {
		unset($_SESSION["cart_item"]);
	}


	/// insert product rating
	public function rateProduct($userId,$code,$rating){
		global $database;

		$database->insert("INSERT INTO `ratings`( `user_id`, `product_code`, `rating`) VALUES ('$userId','$code','$rating')");
	}


}

$cart = new Cart();



?>