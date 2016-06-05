	<div class="nav-container">
		<p class="is-pulled-left logo">
			<a href="/"><img src="/themes/marketplace/images/logo.png" /></a>
		</p>
		<div>
			<nav class="nav mainnav top">
				<div class="nav-right">
					<div class="nav-item">
						<a href="/about" class="menu-item">about</a>
					</div>
					<div class="nav-item">
						<?php
						if($userdata['username'] != 'guest')
						{
							$label = 'Hello, '.$userdata['username'];
							$url = '/user/profile/'.$userdata['username'];
						}
						else
						{
							$label = 'login/register';
							$url = '/user/register';
						}
						?>
						<a href="<?php echo $url; ?>" class="menu-item"><?php echo $label; ?></a>
					</div>
				</div>
			</nav>
			<nav class="nav mainnav bottom">
				<div class="nav-left">
					<div class="nav-item" id="category-link">
						<a href="" class="menu-item menu-trigger">categories</a>
						
					</div>
				</div>
			</nav>
		</div>
	</div>