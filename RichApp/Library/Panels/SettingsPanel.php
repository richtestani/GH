<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library\Panels;
use RichApp\Interfaces;

/**
 * Description of Settings
 *
 * @author richardtestani
 */
class SettingsPanel implements Interfaces\iPanel {
    public function configure()
    {
        $data = array(
            'id' => 'settings',
            'module_name' => 'Settings',
            'icon_class' => 'fa fa-cog'
        );
        
        return $data;
    }
}
