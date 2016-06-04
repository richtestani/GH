<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Library\HTML;

/**
 * Description of Wrap
 *
 * @author richardtestani
 */
class Wrap {
    
    public function __construct(\Library\HTML\Parser $parser)
    {
        $this->parser = $parser;
    }
    
    public function parse($element, $content='')
    {
        
        if(is_array($content))
        {
            //print_r($content);
            $counter = 0;
            foreach($content as $a)
            {
               $wrap[$counter] = $this->parser->parseHTML($a[0]);
               $wrap[$counter]['content'] = $a[1];
               $counter++;
            }
           
            $content = $wrap;
        }
        $html = $this->parser->parseHTML($element); 
        $html['content'] = $content;
        return $html;
    }
    
}
