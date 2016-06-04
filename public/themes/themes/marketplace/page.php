<?php
/*
Template: Home
Description: The default home page
Author: Richard Testani
*/

$page = $api->getPage($slug);
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
		<h1 class="title is-1"><?php echo $page->title; ?></h1>
		<div class="columns">
			<div class="column" id="product-info">
				<?php echo $page->body; ?>
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