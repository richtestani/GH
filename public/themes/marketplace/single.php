<?php
/*
Template: Home
Description: The default home page
Author: Richard Testani
*/
$listing = $api->listing($slug);
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
	<article>
		<h1 class="title is-1"><?php echo $listing['title']; ?>
		<br><small>Sold by: <strong><?php echo $listing['username']; ?></strong></small></h1>
		<div class="columns">
			<div class="column" id="product-images">
				<div class="slider">
					<?php
					foreach($listing['images'] as $i)
					{
						foreach($i as $img)
						{
							echo '<figure class="image">';
							echo '<img src="'.$img['public_path'].'/large/'.$img['filename'].'">';
							echo '</figure>';
						}
					}
					?>
				</div>
				<section class="section">
					<div id="products">
						<?php  ?>
						<strong>In this sale:</strong>
						<?php
					
						foreach($listing['products'] as $p)
						{
							echo '<h3 class="title">'.$p['title'].'</h3>';
							echo '<ul class="menu">';
							echo '<p class="menu-heading">Attributes</p>';
							foreach($p['attributes'] as $a)
							{
								echo '<li class="menu-block">'.$a['name']. ': '.$a['value'].'</li>';
							}
							echo '</ul>';
						}
						?>
					</div>
				</section>
			</div>
			<div class="column" id="product-info">
				
				<h3 class="product-price is-3">$<?php echo money_format('%.2n', $listing['price']); ?></h3>
				
				<form action="/cart/add" method="post">
					<input type="hidden" name="listing_id", value="<?php echo $listing['listing_id']; ?>">
					<input type="hidden" name="qty" value="1" />
					<button type="submit" class="button is-primary" id="add-item-to-cart"><i class="fa fa-shopping-basket"></i> Add to Cart</button>
				</form>
				<div class="message-header">
				    Description
				  </div>
				<div class="message-body">
					<p><?php echo $listing['body']; ?></p>
				</div>
				<ul class="menu">
				<p class="menu-heading">Attributes</p>
				<?php
				foreach($listing['attributes'] as $a)
				{
					echo '<li class="menu-block">'.$a['name']. ': '.$a['value'].'</li>';
				}
				?>
				</ul>
			</div>
		</div>
		
	</article>
</div>
<?php include 'footer.php'; ?>
<script type="text/javascript" src="/themes/marketplace/js/jquery-2.2.0.min.js"></script>
<script src="/themes/marketplace/js/slick-1.5.7/slick/slick.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/themes/marketplace/js/jQuery-menu-aim-master/jquery.menu-aim.js" type="text/javascript" charset="utf-8"></script>
<script src="/themes/marketplace/script.js"></script>
</body>
</html>