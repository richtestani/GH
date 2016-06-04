<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Library\HTML;

/**
 * Description of Block
 *
 * builds a block of HTML
 * Example
 * $block = new Block(new Parser());
 * $block->parse(
 *      'div#wrapper' //wrap with a div
 *      array(  insert many lines
 *          array('span.col', 'line 1'),
 *          array('span.col', 'line 2'),
 *          array('span.col', 'line 3')
 *      )
 * );
 * 
 * @author richardtestani
 */
class Block {
    
    private $parser;
    static protected $counter = 0;
    
    
    public function __construct(\Library\HTML\Parser $parser)
    {
        $this->parser = $parser;
    }
    
    public function parse($element, $content = '')
    {
        self::$counter = 0;
        $this->content = '';
        
        $this->block = $this->parser->parseHTML($element);
        
        if(!empty($content))
        {
            $this->content = $this->parseContent($content);
        }
        
        $this->block['content'] = $this->content;
        return $this->block;
    }
    
    private function parseElement($e)
    {
       return $this->parser->parseHTML($e);
    }
    
    private function parseContent($c)
    {
        $content = array();
        $temp = array();
        $prev = '';
        
        foreach($c as $e)
        {
            
            $el = $e[0];
            $cont = (array_key_exists(1, $e)) ? $e[1] : '';
            
            $elements = explode('>', $el);
            
            $num = count($elements);
            if(count($elements) > 1)
            {
                $counter = 0;
                
                foreach($elements as $f)
                {
                    $result = $this->parser->parseHTML($f);
                    if($num-1 == $counter)
                    {
                        $result;
                        $result['content'] = $cont;
                        $temp['content'][] = $result;
                    }
                    if($counter == 0)
                    {
                        $temp = $result;
                    }
                    $counter++;
                }
                 
            }
            else
            {
                $temp = $this->parser->parseHTML($el);
                $temp['content'] = $cont;
            }
            
            $content[self::$counter] = $temp;
            
            self::$counter++;
        }
        
        return $content;
    }

    
}
