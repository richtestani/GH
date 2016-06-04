<?php
/*
Template: Home
Description: The default home page
Author: Richard Testani
*/

$cart = $api->cartItems($transaction_id);
?>
<html>
<head>
	<title>GH Collectable - An awesome toy shop</title>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
	<link href="https://cdn.rawgit.com/jgthms/bulma/master/css/bulma.min.css" rel="stylesheet" type="text/css">
	<link href="/themes/marketplace/styles.css" rel="stylesheet" media="all">
</head>
<body>
<header class="header">
	<?php include 'nav.php'; ?>
</header>

<div class="container">
	<section class="section">
		<?php if($cart->total != '0.00'): ?>
		<form action="" method="POST" id="marketplace-payment-form" class="form">
  		  <fieldset>
  			  <legend>Customer Information</legend>
			  <div class="form-row">
			    <label>
			      <span>First</span>
			      <input type="text" size="20" name="first" value="" />
			    </label>
			  </div>
			  <div class="form-row">
			    <label>
			      <span>Last</span>
			      <input type="text" size="20" name="last"/>
			    </label>
			  </div>
			  <div class="form-row">
			    <label>
			      <span>Address</span>
			      <input type="text" size="20" name="line1" />
			    </label>
			  </div>
			  <div class="form-row">
			    <label>
			      <span>City</span>
			      <input type="text" size="20" name="city"/>
			    </label>
			  </div>
			  <div class="form-row">
			    <label>
			      <span>State</span>
				  <select name="state">
				  	<option value="AL">Alabama</option>
				  	<option value="AK">Alaska</option>
				  	<option value="AZ">Arizona</option>
				  	<option value="AR">Arkansas</option>
				  	<option value="CA">California</option>
				  	<option value="CO">Colorado</option>
				  	<option value="CT">Connecticut</option>
				  	<option value="DE">Delaware</option>
				  	<option value="DC">District Of Columbia</option>
				  	<option value="FL">Florida</option>
				  	<option value="GA">Georgia</option>
				  	<option value="HI">Hawaii</option>
				  	<option value="ID">Idaho</option>
				  	<option value="IL">Illinois</option>
				  	<option value="IN">Indiana</option>
				  	<option value="IA">Iowa</option>
				  	<option value="KS">Kansas</option>
				  	<option value="KY">Kentucky</option>
				  	<option value="LA">Louisiana</option>
				  	<option value="ME">Maine</option>
				  	<option value="MD">Maryland</option>
				  	<option value="MA">Massachusetts</option>
				  	<option value="MI">Michigan</option>
				  	<option value="MN">Minnesota</option>
				  	<option value="MS">Mississippi</option>
				  	<option value="MO">Missouri</option>
				  	<option value="MT">Montana</option>
				  	<option value="NE">Nebraska</option>
				  	<option value="NV">Nevada</option>
				  	<option value="NH">New Hampshire</option>
				  	<option value="NJ">New Jersey</option>
				  	<option value="NM">New Mexico</option>
				  	<option value="NY">New York</option>
				  	<option value="NC">North Carolina</option>
				  	<option value="ND">North Dakota</option>
				  	<option value="OH">Ohio</option>
				  	<option value="OK">Oklahoma</option>
				  	<option value="OR">Oregon</option>
				  	<option value="PA">Pennsylvania</option>
				  	<option value="RI">Rhode Island</option>
				  	<option value="SC">South Carolina</option>
				  	<option value="SD">South Dakota</option>
				  	<option value="TN">Tennessee</option>
				  	<option value="TX">Texas</option>
				  	<option value="UT">Utah</option>
				  	<option value="VT">Vermont</option>
				  	<option value="VA">Virginia</option>
				  	<option value="WA">Washington</option>
				  	<option value="WV">West Virginia</option>
				  	<option value="WI">Wisconsin</option>
				  	<option value="WY">Wyoming</option>
				  </select>
			    </label>
			  </div>
			  <div class="form-row">
			    <label>
			      <span>Zip</span>
			      <input type="text" size="20" name="zip"/>
			    </label>
			  </div>
			  <div class="form-row">
			    <label>
			      <span>Email</span>
			      <input type="text" size="20" name="email"/>
			    </label>
			  </div>
		  </fieldset>
		  <fieldset>
			  <legend>Paymeny Information</legend>
			  <span class="payment-errors"></span>

			  <div class="form-row">
			    <label>
			      <span>Card Number</span>
			      <input type="text" size="20" data-stripe="number"/>
			    </label>
			  </div>

			  <div class="form-row">
			    <label>
			      <span>CVC</span>
			      <input type="text" size="4" data-stripe="cvc"/>
			    </label>
			  </div>

			  <div class="form-row columns">
			    <div class="column">
				    <label>
				      <span>Expiration Month (MM)</span>
				      <input type="text" size="2" data-stripe="exp-month"/>
				    </label>
				</div>
			    <div class="column">
				    <span> Expiration Year (YYYY) </span>
				    <input type="text" size="4" data-stripe="exp-year"/>
				</div>
			  </div>
			  <div id="summary">
				  <h4 class="title is-4">Cart Summary</h4>
				  <p><span>SubTotal:</span><?php echo $cart->total; ?></p>
			  	 <p><span>Shipping:</span><?php echo $cart_settings['shipping']; ?></p>
				 <p><span>Total:</span><?php echo $cart->total + $cart_settings['shipping']; ?></p>
			  </div>
		  </fieldset>
		  <button type="submit" id="payment-submit" class="button">Submit Payment</button>
		</form>
		<?php else:?>
			<h2 class="title is-2">You must have items in your cart before you can checkout</h2>
		<?php endif; ?>
	</section>
</div>

<?php include 'footer.php'; ?>
<script type="text/javascript" src="/themes/marketplace/js/jquery-2.2.0.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="/themes/marketplace/checkout.js"></script>
</body>
</html>
