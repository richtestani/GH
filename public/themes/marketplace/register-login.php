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
	<section class="section">
		
			<?php
			if(isset($errorMessage)):?>
				<div class="notification is-warning">
				  <?php echo $errorMessage; ?>
				</div>
			
			<?php endif; ?>
			<?php if($showForm == 'register'): ?>
		<form action="/user/save" method="POST" id="marketplace-payment-form" class="form">
			<div>
				Already a member? <a href="/user/login">Login</a>
			</div>
  		  <fieldset>
  			  <legend>Registration: Who Are You?</legend>
			  <div class="form-row">
			    <label>
			      <span>First</span>
			      <input type="text" size="20" name="first"/>
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
			      <span>Email</span>
			      <input type="text" size="20" name="email"/>
			    </label>
			  </div>
			  <div class="form-row">
			    <label>
			      <span>Username</span>
			      <input type="text" size="20" name="username"/>
			    </label>
			  </div>
			  <div class="form-row">
			    <label>
			      <span>Password</span>
			      <input type="text" size="20" name="password"/>
				  <span>You password should include letters &amp; numbers and be at least 8 charaters</span>
			    </label>
			  </div>
			  <button type="submit" id="user-create-submit" class="button">Create Me</button>
		  </fieldset>
		</form>
		<?php else: ?>
    	<form action="/user/login" class="form" method="post">
			<div>
				Not a member? <a href="/user/register">Register</a>
			</div>
      		  <fieldset>
      			  <legend>Login</legend>
    			  <div class="form-row">
    			    <label>
    			      <span>Username</span>
    			      <input type="text" size="20" name="username"/>
    			    </label>
    			  </div>
    			  <div class="form-row">
    			    <label>
    			      <span>Password</span>
    			      <input type="password" size="20" name="password"/>
    			    </label>
    			  </div>
  			  <button type="submit" id="user-create-login" class="button">Login</button>
  		  </fieldset>
	  </form>
		<?php endif; ?>
	</section>
</div>

<?php include 'footer.php'; ?>
<script type="text/javascript" src="/themes/marketplace/js/jquery-2.2.0.min.js"></script>
<script src="/themes/marketplace/js/jQuery-menu-aim-master/jquery.menu-aim.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>
