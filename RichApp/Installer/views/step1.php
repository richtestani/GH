<!DOCTYPE html> 
<html>
    <head>
        <title>Welcome to RichApp CMS</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <style>
            html, body {
                width: 100%;
                height: 100%;
                padding: 0;
                margin: 0;
            }
            body {
                background-color: #D7DAE6;
            }
            .cenetered-box {
                position: relative;
            }
            .centered-box>div {
                margin: auto;
            }
            
            .content-box {
				width: 75%;
				margin: 0 auto;
				font-size:18px;
				font-weight:100;
            }
            .installer-app-box {
                position: relative;
                background-color: white;
                border-bottom: 1px solid #A4A7B6;
                padding: 10px;
                border-radius: 2px;
            }
            .richapp-title {
                background-image: url(/assets/admin/images/richapp-logo.png);
                background-repeat: no-repeat;
                background-position: center;
                background-size: contain;
                width: 70%;
                height: 50px;
                margin: 0 auto;
                text-indent: -999px;
            }
			form label {
				font-weight: 300;
				font-size: 20px;
			}
			form button {
				margin-top: 20px;
			}
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div class="container">
                <div class="cenetered-box">
                    <div>
                        <div class="col-xs-8 col-md-8 col-xs-push-2 col-md-push-2">
                        <h2 class="richapp-title">RichApp</h2>
                        <div class="installer-app-box">
							<?php
							if(array_key_exists('install__db_error', $_SESSION))
							{
								echo '<p>Could not connect to the database, please try again</p>';
							}
							?>
                            <div class="content-box clearfix">
                                <p>
                                    Give yourself a username and password.<br>This is what will be used to login to the administrative area.
                                </p>
                                <form method="post" action="/installer/step2">
                                    <div class="control-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" />
                                    </div>
                                    <div class="control-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" />
                                    </div>
                                    <button type="submit" class="btn btn-default">Step 2</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>
