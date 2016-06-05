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
		<h1 class="title is-1">Your Cart <small class="is-right">
			<a href="/cart/checkout" class="button">Checkout</a>
		</small></h1>
		
		<table class="table is-striped" id="cart">
			<form method="post" action="/cart/update">
			<thead>
				<tr>
					<th>Item</th>
					<th>Quantity</th>
					<th>Unit Price</th>
					<th>Line Price</th>
					<th>Remove</th>
				</tr>
			</thead>
		<?php
	
		if(count($cart->lines) == 0)
		{
			echo 'You have no items in your cart';
		}
		foreach($cart->lines as $l)
		{
			$line = unserialize($l['item']);
			echo '<tr data-item-id="'.$line['listing_id'].'">';
			echo '<td class="table-link"><a href="/listing/'.$line['slug'].'">'.$line['title'].'</a></td>';
			echo '<td><input type="text" name="qty[]" class="qty" value="'.$l['qty'].'"></td>';
			echo '<td>$'.$line['price'].'</td>';
			echo '<td>$'.money_format('%.2n', ($l['qty'] * $line['price'])).'</td>';
			echo '<td><span class="icon"><a href="/cart/remove/'.$line['listing_id'].'" class="fa fa-remove"></a></span></td>';
			echo '<input type="hidden" name="item_id[]" value="'.$line['listing_id'].'" />';
			echo '</tr>';
		}
		?>
		<tr class="subtotal">
			<td class="is-right" colspan="4">Total: $<?php echo $cart->total; ?></td>
		</tr>
		<tr>
			<td><button>Update</button></td>
		</tr>
		</form>
		</table>
	</section>
</div>

<?php include 'footer.php'; ?>
<script type="text/javascript" src="/themes/marketplace/js/jquery-2.2.0.min.js"></script>
<script src="/themes/marketplace/script.js"></script>
</body>
</html>
