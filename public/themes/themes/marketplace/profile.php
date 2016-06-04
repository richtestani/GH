<?php
/*
Template: Home
Description: The default home page
Author: Richard Testani
*/
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
	<section id="featured" class="section">
		<div class="hero-content">
		    <div class="container">
		      <h1 class="columns title is-1">
		        <div class="column is-1"><div class="profile-ring"></div></div><div class="profile-name column"><?php echo $title; ?></div>
		      </h1>
		      <h2 class="subtitle">
		       
		      </h2>
		    </div>
		  </div>
	</section>
	<section class="section structure">
			<div class="columns">

				<?php if(isset($sellerdata)): ?>
				<?php if(isset($userdata) && $username == $userdata['username']): ?>
					<div class="column">
						<a href="https://connect.stripe.com/oauth/authorize?response_type=code&scope=read_write&client_id=ca_7t8Okz94hsxygtBpLZFEAbHcy7KmJteq" class="button is-primary">Connect to Stripe</a>
					</div>
				<?php endif; ?>
				<div class="column">
					<h3 class="title is-3">Seller Information</h3>
					
					<div class="menu">
						<strong class="menu-header">Ships From:</strong>
						<p>Address: <?php echo $sellerdata->address1; ?></p>
						<p>City: <?php echo $sellerdata->city; ?></p>
						<p>State: <?php echo $sellerdata->state; ?></p>
						<p>Zip: <?php echo $sellerdata->zip; ?></p>
					</div>
				</div>
			<?php endif; ?>
			<?php if(!empty($listings)): ?>
				<div class="column">
					<h3 class="title is-3">Active Listings</h3>
					<?php
					foreach($listings as $l)
					{

						echo '<article class="media listing-item">';
						echo '<figure class="media-image"><img src="http://placehold.it/60x60"></figure>';
						echo '<div class="media-content">';
						echo '<div class="columns">';
						echo '<div class="column is-8">';
						echo '<h4 class="title is-4">'.$l['title'].'</h4>';
						echo '</div>';
						echo '<div class="column">';
						echo '<div class="is-right">';
						echo '<p>$'.$l['price'].'</p>';
						echo '<a class="button is-primary" href="/listing/'.$l['slug'].'">Details</a>';
						echo '</div>';
						echo '</div>';
						echo '<div>';
						echo '</div>';
						echo '</article>';
					}
					?>
				</div>
			<?php endif; ?>
				<div class="column">
					<h3 class="title is-3">My Purchases</h3>
					<?php
					$orders = $api->getOrderHistory($userdata['id']);
					foreach($orders as $o)
					{
						echo '<div class="card">';
						echo $o['transaction_id'].' | ';
						echo '$'.$o['amount_charged'];
						echo '<br>purchased on: '.$o['completed_on'];
						if($o['shipping_status']==0)
						{
							echo '<a href="http://www.stripe.com'.$o['refund_url'].'">Refund</a>';
						}
						echo '</div>';
					}
					?>
				</div>
			</div>
	</section>
</div>
<?php include 'footer.php'; ?>
<script type="text/javascript" src="/themes/marketplace/js/jquery-2.2.0.min.js"></script>
<script src="/themes/marketplace/js/jQuery-menu-aim-master/jquery.menu-aim.js" type="text/javascript" charset="utf-8"></script>
<script src="/themes/marketplace/script.js"></script>
</body>
</html>