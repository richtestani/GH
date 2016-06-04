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
				font-size: 18px;
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
                                <p><strong>Welcome to RichApp!</strong>
                                An easy to use and manage
                                content system. </p><p>
                                There are 3 steps to get you going, 
                                and this isn't one of them.
                                </p>
                                <a role="button" href="/installer/step1" class="pull-right btn btn-default">Lets Go</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>
