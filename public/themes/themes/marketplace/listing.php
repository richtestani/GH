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
		      <h1 class="title is-1">
		        <?php echo $title; ?>
		      </h1>
		      <h2 class="subtitle">
		        Items for sale
		      </h2>
		    </div>
		  </div>
	</section>
	<section class="section structure">
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
	</section>
</div>
<?php include 'footer.php'; ?>
<script type="text/javascript" src="/themes/marketplace/js/jquery-2.2.0.min.js"></script>
<script src="/themes/marketplace/js/jQuery-menu-aim-master/jquery.menu-aim.js" type="text/javascript" charset="utf-8"></script>
<script src="/themes/marketplace/script.js"></script>
</body>
</html>