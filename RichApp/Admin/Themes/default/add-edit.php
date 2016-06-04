<!DOCTYPE html> 
<html>
    <head>
        <title>Welcome to RichApp CMS</title>
		<link href='https://fonts.googleapis.com/css?family=Cabin:400,400italic,500,700,600' rel='stylesheet' type='text/css'>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
		<link href="http://code.jquery.com/ui/1.11.4/themes/black-tie/jquery-ui.css" rel="stylesheet">
        <link href="/assets/admin/js/lib/medium-editor/dist/css/medium-editor.min.css" rel="stylesheet" type="text/css"/>
		<link href="/assets/admin/js/lib/medium-editor/dist/css/themes/flat.min.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="/assets/admin/css/Hover-master/css/hover-min.css" type="text/css" media="screen">
		<link rel="stylesheet" href="/assets/admin/css/loaders-css/loaders.min.css" type="text/css" media="screen">
        <link href="/assets/admin/css/admin.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div id="admin">
            <?php include 'menu.php'; ?>
            <div id="working-area">
                <div id="title-strip"><h1 id="page_title"><?php echo (isset($page_title)) ? 'Editing: '.$page_title : 'Untitled Page'; ?></h1></div>
                <div id="main-content" class="add-edit max">
                    <?php echo $form; ?>
                </div>
                <?php echo $panels; ?>
            </div>
        </div>
        <div id="upload-form" class="upload-form-content">
            <form action="/images/upload">
                <h2>Uploader</h2>
                <div class="form-group-lg">
                    <label>Choose an Image</label>
                    <input type="file" id="image-file-upload" />
                </div>
            </form>
        </div>
            <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
		<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
            <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="http://feather.aviary.com/imaging/v3/editor.js"></script>
            <script src="/assets/admin/js/lib/js-cookie/src/js.cookie.js" type="text/javascript"></script>
		    <script src="/assets/admin/css/loaders-css/loaders.css.js" type="text/javascript" charset="utf-8"></script>
            <script src="/assets/admin/js/menus.js" type="text/javascript"></script>
            <script src="/assets/admin/js/slide-panel.js" type="text/javascript"></script>
			<script src="/assets/admin/js/lib/transit/jquery.transit.min.js" type="text/javascript"></script>
            <script src="/assets/admin/js/RAeditor.js" type="text/javascript"></script>
            <script src="/assets/admin/js/Uploader.js" type="text/javascript"></script>
			<script src="/assets/admin/js/lib/medium-editor/dist/js/medium-editor.min.js" type="text/javascript"></script>
            <script src="/assets/admin/js/richapp.js" type="text/javascript"></script>
			<?php
			if(isset($script))
			{
				foreach($script as $s)
				{
					echo '<script src="'.$s.'"></script>';
				}
			}
			?>
    </body>
</html>