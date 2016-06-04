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
                            <div class="content-box clearfix">
                                <p>
									
                                   <?php
                                   if(!empty($status))
                                   {
                                       echo 'hmmm. <h4 class="alert alert-warning"><small>'.$status.'</small></h4>';
                                   }
                                   ?>
                                </p>
                                <?php
                                if($continue):
                                    ?>
								<h2 class="page-title">The database setup</h2>
								
                                <form action="/installer/step3" method="post">
									<div class="form-group">
									    <label for="host">Host</label>
									    <input type="text" class="form-control" id="host" name="host">
									  </div>
									  <div class="form-group">
									    <label for="database">Database Name</label>
									    <input type="text" name="database" class="form-control" id="database">
									  </div>
									  <div class="form-group">
									    <label for="dbuser">Database Username</label>
									    <input type="text" name="dbuser" class="form-control" id="dbuser">
									  </div>
									  <div class="form-group">
									    <label for="dbpass">Database Password</label>
									    <input type="text" name="dbpass" class="form-control" id="dbpass">
									  </div>
                                    <button type="submit" class="btn btn-default">Step 3</button>
                                </form>
                                <?php else: ?>
                                refresh this page once your changes have been made.
                                <?php endif; ?>
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
