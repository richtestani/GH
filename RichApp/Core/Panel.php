<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Core;

/**
 * Description of Panel
 *
 * @author richardtestani
 */
class Panel {
    
    private $registeredPanels = [];
    protected $panels = [];
    protected $output = '';
	
	public function __construct()
	{
		$default_panels = array('images, meta, settings, tags');
		
	}
    
    public function register($panel)
    {
        $this->registeredPanels[] = $panel;
    }
    
    public function setup()
    {
        $panelContent = '<ul class="list-unstyled" id="ra-slide-panel-tabs">';
        $panelContent .= '<div id="ra-slide-panel-content">';
        foreach($this->registeredPanels as $panel)
        {
            $panelData = $panel->configure();
            if(array_key_exists('id', $panelData) && !array_key_exists($panelData['id'], $this->panels))
            {
                 $this->panels[$panelData['id']] = $panel;
				 
				 //icon
				 $icon = (array_key_exists('icon_class', $panelData)) ? $panelData['icon_class'] : 'fa fa-gear';
				 
				 //set module name
				 $moduleName = array_key_exists('module_name', $panelData) ? $panelData['module_name'] : 'Untitled Panel';
				 
				 //list item
				 $panelContent .= '<li class="slide-toggle" data-menu-id="'.$panelData['id'].'"><a href="" class="panel-link hvr-fade"><span class="'.$icon.'">'.$moduleName.'</span></a>';
				 
				 $panelContent .= '<div data-content-id="'.$panelData['id'].'" data-module-name="'.$moduleName.'" class="ra-content">';
				 
				 $panelContent .= '<div id="ra-slide-panel-title"></div></div></li>';
				 
				 
            }
        }
        
        $panelContent .= '</ul><!-- end ul -->';
        $panelContent .= '</div>';
        $this->output = $panelContent;
    }
    
    public function get()
    {
        return '<aside id="ra-slide-panel" class="min"><div class="loader"><div class="loader-inner line-scale"><div></div><div></div><div></div><div></div><div></div></div></div><div id="ra-slide-panel-control">'.$this->output.'</div></div>';
    }
    
}
