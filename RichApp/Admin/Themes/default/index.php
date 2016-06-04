<!DOCTYPE html> 
<html>
    <head>
        <title>Welcome to RichApp CMS</title>
		<link href='https://fonts.googleapis.com/css?family=Cabin:400,400italic,500,700,600' rel='stylesheet' type='text/css'>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="/assets/admin/css/Hover-master/css/hover-min.css" type="text/css" media="screen">
        <link href="/assets/admin/css/admin.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div id="admin">
            <?php include 'menu.php'; ?>
            <div id="working-area">
                <div id="title-strip"><h1>Dashboard</h1></div>
                <div id="widgets">
                    <?php
					foreach($dashboardWidgets as $k => $widget)
					{
						$data = $widget();
						echo '<div class="widget-box col-xs-6">';
						echo '<div class="widget-title">';
						echo $data['title'];
						echo '</div>';
						echo $data['content'];
						echo '</div>';
					}
                    ?>
                </div>
            </div>
        </div>
            <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
			<script src="/assets/admin/js/lib/js-cookie/src/js.cookie.js" type="text/javascript"></script>
            <script src="/assets/admin/js/menus.js" type="text/javascript"></script>
            <script src="/assets/admin/js/richapp.js" type="text/javascript"></script>
    </body>
</html>