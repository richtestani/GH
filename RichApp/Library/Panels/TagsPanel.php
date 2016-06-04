<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library\Panels;

/**
 * Description of TagsPanel
 *
 * @author richardtestani
 */
class TagsPanel implements \RichApp\Interfaces\iPanel {
    public function configure() {
        $data = array(
            'id' => 'tags',
            'module_name' => 'Tags',
            'icon_class' => 'fa fa-tags'
        );
        
        return $data;
    }
}
