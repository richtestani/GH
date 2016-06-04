<!doctype html>
<html>
    <head>
        <title><?php echo $page; ?> | RLTCMS</title>
        <?php
        if($page == 'add')
        {
            echo '<link href="http://fnt.webink.com/wfs/webink.css?project=8EF74517-96C1-43D5-AA98-7375BCADEC8E&fonts=89A66581-A3F4-3AAE-45FD-F57659794979:family=FaktPro-Blond,A65E74B3-95D4-8C3A-5583-87B47A9527A2:family=FaktPro-Thin,8DB75F5E-A05D-2E1E-51C7-C27AF85E5C4D:family=FaktPro-Hair,AF60E68C-0360-93CD-D9DF-3CEFF97CF5F8:family=FaktPro-Air,659C73BE-BC4B-2759-FF27-07CB31B70258:family=FaktPro-Medium,A563A792-A752-545C-D05E-55E5B77EF026:family=FaktPro-Normal" rel="stylesheet" type="text/css"/>
';
        }
        ?>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/js/lib/labelbeauty/source/jquery-labelauty.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/js/lib/switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/js/lib/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/js/lib/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/js/lib/pushy/css/pushy.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/css/admin.css" type="text/css" rel="stylesheet" />
		<?php echo (isset($css)) ? $css : ''; ?>
    </head>
    
    <body>
        <div id="wrapper">
            <nav class="pushy pushy-left" role="navigation" id="package-navigation">
                <?php echo $package_nav; ?>
            </nav>
            <!-- Site Overlay -->
            <div class="site-overlay"></div>
            <div class="rlt-content" id="container">
                <header class="rlt-header">
                    <nav class="rlt-nav dropdown" id="main-nav">
                        <?php
                            foreach($mainnav as $n)
                            {
                                
                                
                                if(isset($settings_menu) AND $n['name'] == 'settings')
                                {
                                    echo '<a href="'. $n['href'] .'" id="setting-drop" data-target="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">';
                                    echo ucwords($n['name']).'<span class="caret"></span>';
                                    echo $settings_menu;
                                }
                                else 
                                {
                                    echo '<a href="'. $n['href'] .'">';
                                    echo ucwords($n['name']);
                                }
                                echo '</a> | ';
                            }
                        ?>
                        <a href="#package-navigation" class="side-menu menu-btn"><?php echo $package_name; ?></a>
                        <small class="pull-right">Roloto CMS version <?php echo APP_VERSION; ?></small>
                    </nav>
                    <?php 
					echo (isset($second_menu)) ? $second_menu : ''; ?>
                </header>
                    
    