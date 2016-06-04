<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library\Panels;
use RichApp\Interfaces;

/**
 * Description of Meta
 *
 * @author richardtestani
 */
class MetaPanel implements Interfaces\iPanel {
    public function configure()
    {
        $data = array(
            'id' => 'meta',
            'module_name' => 'Meta',
            'icon_class' => 'fa fa-file-code-o'
        );
        
        return $data;
    }
}
