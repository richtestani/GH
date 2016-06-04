<?php
//print_r($package_config);

if(array_key_exists('Controllers', $package_config))
{
	//$controllers['marketplace'] = $package_config['Controllers']['marketplace'];
	foreach($package_config['Controllers'] as $k => $c)
	{
		if(array_key_exists($k, $nav))
		{
			$actions = $c['actions'];
			$key = key($c['actions']);
			$nav[$k]['actions'][$key] = $actions[$key];
		}
		else
		{
			$nav[$k] = $c;
		}
	}
	
	if(array_key_exists('Dashboard', $package_config))
	{
		$dash = $package_config['Dashboard'];
		foreach($dash['modules'] as $k => $d)
		{
			$dashboard['modules'][$k] = $d;
		}
	}
	//echo '<li><div class="sidebox-item" data-menu-name="extend"><a href="/admin/extend"><span class="fa fa-asterisk"></span></a></div></li>';
}

$main_nav = '<ul class="list-unstyled" id="nav-main">';
$nav_pages = '';
foreach($nav as $item => $controller)
{
	$icon = (array_key_exists('icon', $controller)) ? $controller['icon'] : 'fa fa-circle';
	$label = (array_key_exists('label', $controller)) ? $controller['label'] : $item;
	$main_nav .= '<li><div class="sidebox-item" data-menu-name="'.$item.'"><a href="/admin/'.$item.'" class="hvr-fade"><span class="'.$icon.'"></span><span class="nav-label">'.$label.'</span></a></div></li>';

	if(!array_key_exists('actions', $controller) || empty($controller['actions']))
	{
		continue;
	}
	
	$action = $controller['actions'];
	$nav_pages .= '<ul class="list-unstyled '.$item.' hidden">';
	$nav_pages .= '<h5 class="menu-title"><strong>'.$item.'</strong></h5>';
	foreach($action as $k => $a)
	{
		if(array_key_exists('group', $a))
		{
			$group = $a['group'];
			$nav_pages .= '<strong class="group-title">'.$k.'</strong>';
			foreach($group as $r => $g)
			{
				
				$aicon = (array_key_exists('icon', $g)) ? $g['icon'] : 'fa fa-circle-thin';
				$route = (array_key_exists('route', $g)) ? $g['route'] : '#';
				$nav_pages .= '<li><div class="sidebox-item-action" data-menu-name="'.strtolower($r).'"><a href="'.$route.'"><span class="'.$aicon.'"></span><span class="sub-nav-label">'.$r.'</span></a></div></li>';
			}
		}
		else
		{
			$aicon = (array_key_exists('icon', $a)) ? $a['icon'] : 'fa fa-circle-thin';
			$route = (array_key_exists('route', $a)) ? $a['route'] : '#';
			$nav_pages .= '<li><div class="sidebox-item-action" data-menu-name="'.strtolower($k).'"><a href="'.$route.'"><span class="'.$aicon.'"></span><span class="sub-nav-label">'.$k.'</a></a></div></li>';
		}
		
	}
	$nav_pages .= '</ul>';
}

$main_nav .= '</ul>';

?>
<div id="ra-nav" data-menu-size="max">
    <div class="sidebox" id="main-menu" data-open-state="open">
        <ul class="list-unstyled" id="nav-main">
            
			<?php
			echo $main_nav;
			?>
        </ul>
</div>
<div class="sidebox open-state-closed" id="action-menu" data-open-state="closed">
    <span class="tab"></span>
    <ul id="nav-sub">
        <!-- base pages -->
        <?php echo $nav_pages;?>
    </ul>
</div>
</div>