<!doctype html5>
<?php
/*
Template: Home
Description: The default home page
Author: Richard Testani
*/
$featured = $api->getFeatured();
//print_r($featured);
?>
<html>
<head>
	<title>GH Collectable - An awesome toy shop</title>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
	<link href="/themes/marketplace/bulma.min.css" rel="stylesheet" type="text/css">
	<link href="/themes/marketplace/styles.css" rel="stylesheet" media="all">
</head>
<body>
	<header class="header">
		<?php include 'nav.php'; ?>
	</header>
	<section id="featured">
		<div class="featured-content">
	      <?php
		  foreach($featured as $f)
		  {
			  $img = $api->getImage($f['image_id']);
			  echo '<div class="featured-image" style="background-image: url('.$img['public_path'].'/x-large/'.$img['filename'].');">test</div>';
		  }
	      ?>
		  </div>
	</section>
	<div class="container">
		<section class="section">
			<h3 class="title is-3">Popular Listings</h3>
			<div id="recent" class="columns">
				<?php
				$listings = $api->recentListings(5);
				foreach($listings as $l):?>
			
				<div class="column card">
					<?php
				
					if($l['default_image'] != 0)
					{
						$img = $api->getImage($l['default_image']);
					}
					?>
				  <figure class="card-image is-4x3">
				    <img src="<?php echo $img->public_path;?>/medium/<?php echo $img->filename; ?>" alt="">
				  </figure>
				  <div class="card-content">
				    <div class="media">
				      <figure class="media-image is-40">
				        <img src="http://placehold.it/40x40" alt="Image">
				      </figure>
				      <div class="media-content">
				        <p class="title is-5"><a href="/listing/<?php echo $l['slug']; ?>"><?php echo $l['title']; ?></a></p>
				        <p class="subtitle is-6">Sold By: <a href="/user/profile/<?php echo $l['userslug']; ?>"><?php echo $l['username']; ?></a></p>
				      </div>
				    </div>

				    <div class="content">
				      <?php echo $l['body']; ?>
				    </div>
				  </div>
				</div>
			
			<?php endforeach; ?>
			</div>
		</section>
		<section class="section">
			<h3 class="title is-3">Fantastic Listings</h3>
			<div id="recent" class="columns">
				<?php
				$listings = $api->recentListings(5);
				foreach($listings as $l):?>
			
				<div class="column card">
					<?php
				
					if($l['default_image'] != 0)
					{
						$img = $api->getImage($l['default_image']);
					}
					?>
				  <figure class="card-image is-4x3">
				    <img src="<?php echo $img->public_path;?>/medium/<?php echo $img->filename; ?>" alt="">
				  </figure>
				  <div class="card-content">
				    <div class="media">
				      <figure class="media-image is-40">
				        <img src="http://placehold.it/40x40" alt="Image">
				      </figure>
				      <div class="media-content">
				        <p class="title is-5"><a href="/listing/<?php echo $l['slug']; ?>"><?php echo $l['title']; ?></a></p>
				        <p class="subtitle is-6">Sold By: <a href="/user/profile/<?php echo $l['userslug']; ?>"><?php echo $l['username']; ?></a></p>
				      </div>
				    </div>

				    <div class="content">
				      <?php echo $l['body']; ?>
				    </div>
				  </div>
				</div>
			
			<?php endforeach; ?>
			</div>
		</section>
	</div>
	<?php include 'footer.php'; ?>
	<script type="text/javascript" src="/themes/marketplace/js/jquery-2.2.0.min.js"></script>
	<script src="/themes/marketplace/js/jQuery-menu-aim-master/jquery.menu-aim.js" type="text/javascript" charset="utf-8"></script>
	<script src="/themes/marketplace/script.js"></script>
</body>
</html>