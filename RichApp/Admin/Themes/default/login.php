<!DOCTYPE html> 
<html>
    <head>
        <title>Welcome to RichApp CMS</title>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
		<link href="/assets/admin/css/admin.css" rel="stylesheet">
    </head>
    <body id="login">
        <div id="wrapper">
            <div class="container">
                <div class="cenetered-box">
                    <div>
                        <div class="col-xs-8 col-md-8 col-xs-push-2 col-md-push-2">
                        <h2 class="richapp-title">RichApp</h2>
                        <div class="installer-app-box">
                            <div class="content-box clearfix">
                                <?php
								if($action == 'reset') {
									?>
									<form method="post" action="/admin/passwordreset">
											<label>Enter your email, and a an email to instruct how to reset your password will be sent.</label>
											<input type="email" value="" name="email" placeholder="your email" class="form-control" />
											 <button type="submit" class="btn btn-default">Reset</button>
									</form>
									<?php
								} elseif($action == 'passwordform') {
									?>
									<form method="post" action="/admin/passwordreset/new">
											<label>Reset your password</label>
											<input type="password" value="" name="newpass" placeholder="your new password" class="form-control" />
											 <button type="submit" class="btn btn-default">Reset</button>
									</form>
									<?php
								} else {
									?>
									
	                                <p>
	                                    Login
	                                </p>
	                                <form class="form-horizontal" action="/admin/logincheck" method="post">
	                                    <div class="control-group">
	                                        <label>username</label>
	                                        <input type="text" name="username" class="form-control" />
	                                    </div>
	                                    <div class="control-group">
	                                        <label>password</label>
	                                        <input type="password" name="password" class="form-control" />
	                                    </div>
	                                    <button type="submit" class="btn btn-default">Login</button>
										<a href="/admin/lostpass">Forgot your password?</a>
	                                </form>
								<?php
								}
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>
