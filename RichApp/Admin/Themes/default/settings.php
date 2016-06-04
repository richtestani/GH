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
                <div id="title-strip"><h1><?php echo (isset($page_title)) ? $page_title : 'Untitled Listing'; ?></h1></div>
                <div id="main-content">
                    <div class="table-layout table-responsive">
						<table class="table table-striped">
							<?php $start = 0; ?>
		                    <?php foreach($listing as $listing):?>
								
								<?php if($start == 0): ?>
									<!-- header -->
									<tr>
					                    <?php foreach($listing as $k => $line):?>
											<th><?php echo ucwords(str_replace('_', ' ', $k)); ?></th>
										<?php endforeach; ?>
										<th><?php echo ucwords(str_replace('_', ' ', 'edit')); ?></th>
										<th><?php echo ucwords(str_replace('_', ' ', 'delete')); ?></th>
									</tr>
								<?php endif; ?>
								<tr>
								<!-- body -->
			                    <?php foreach($listing as $k => $line):?>
									<td><?php echo $line; ?></td>
								<?php endforeach; ?>
								<td><a class="btn btn-default hvr-fade modify-link edit-link" href="/admin/<?php echo $basepath;?>/edit/<?php echo $listing['id']; ?>">Edit</a></td>
								<td><a class="btn btn-default hvr-fade modify-link delete-link" href="/admin/<?php echo $basepath;?>/delete/<?php echo $listing['id']; ?>">Delete</a></td>
								</tr>
								<?php $start++;?>
							<?php endforeach; ?>
						</table>
						<?php
						if(isset($pagination))
						{
							echo $pagination;
						}
						?>
					</div>
                </div>
                <?php //echo $panels; ?>
            </div>
        </div>
            <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
            <script src="/assets/admin/js/lib/js-cookie/src/js.cookie.js" type="text/javascript"></script>
            <script src="/assets/admin/js/menus.js" type="text/javascript"></script>
            <script src="/assets/admin/js/slide-panel.js" type="text/javascript"></script>
            <script src="/assets/admin/js/editor.js" type="text/javascript"></script>
            <script src="/assets/admin/js/richapp.js" type="text/javascript"></script>
    </body>
</html>