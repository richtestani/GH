<?php
/*
Template: Home
Description: The default home page
Author: Richard Testani
*/
?>
<!doctype html5>
<html>
	<head>
		<title>RichApp Home</title>
		<style>
		body { margin: 0; padding: 0;}
		.container { width: 960px; margin: 0 auto; }
		a {color: black;}
		a:hover {color: gray;}
		h1 {font-size:48px;}
		h2 {font-size:36px;}
		h3 {font-size:28px;}
		h4 {font-size:20px;}
		.title { width: 100%; font-family: Georgia; font-weight: 300;}
		.title a {
			display: block;
			border-bottom: 1px solid #800200;
			text-decoration: none;
			margin-bottom: 5px;
		}
		.title small {display:block; font-size: 0.5em;}
		header { background-color: #262626; padding: 10px; }
		.site a { color: white;}
		.go-right {float: right; font-size: 0.4em; color: gray; line-height: 4em;}
		.go-right a {text-decoration: none; display: inline-block; border-bottom: none; color: black;}
		</style>
	</head>
	<body>
		<header>
			<div class="container">
				<div class="site"><a href="/">RichApp</a></div>
			</div>
		</header>
		<div class="container">
			<?php
			
			foreach($pages as $page)
			{
				$category = $api->getPageCategory($page['category']);
				echo '<h2 class="title">';
				if(!empty($category))
				{
					echo '<div class="go-right"><span>categorized in <a href="/category/'.$category['slug'].'">'.$category['category'].'</a></span></div>';
					
				
				}
				echo '<a href="/'.$page['slug'].'">'.$page['title'].'</a><small>'.$page['subtitle'].'</small>';
				echo '</h2>';
				echo '<div class="body">';
				echo $page['body'];
				echo '</div>';

			}
		
			?>
		</div>
	</body>
</html>
