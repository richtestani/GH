<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Library\HTML;

/**
 * Description of Structure
 *
 * @author richardtestani
 */
class Structure {

    private $stack = array();
    protected $close_tags = array();
    static protected $counter = 0;
    static protected $tags = array();
    protected $is_nesting = false;
    protected $structure;
    protected $builders = array();
    
    
    public function build($element='')
    {

        if(empty($this->builders))
        {
            $this->builders = $element;
        }
        else
        {
            $element = $this->builders;
            $this->builders = '';
            $html = $this->parse();
            $this->close();
            $this->wrap($element, $html);

        }
        return $this;
    }
    
    /*
     * generates a block of content
     * where an outer tag encloses
     * many other tags within itself
     */
    public function block($wrapper, $inner = '', $inner_content ='')
    {
        $block = new \Library\HTML\Block(new \Library\HTML\Parser());
        $html = $block->parse($wrapper, $inner, $inner_content);
        
        $this->stack($html);
        
        return $this;
        
    }
    
    /*
     * builds a single html element
     * open and close if it doesnt self close
     */
    public function element($string, $content='')
    {
        $parser = new \Library\HTML\Parser();
        $html = $parser->parseHTML($string);
        $html['content'] = $content;
        $this->stack($html);
        return $this;
    }
    
    /*
     * takes a string and wraps it with an element
     */
    public function wrap($string, $content = '')
    {
        $wrap = new \Library\HTML\Wrap(new \Library\HTML\Parser());
        $html = $wrap->parse($string, $content);
        
        $this->close()->stack($html);
        return $this;
    }
    
    public function nest($element, $content = null)
    {
        $c = array();
        $cont = '';
        if(!empty($content) AND !is_array($content))
        {
            $recent = $this->stack[count($this->stack)-1];
            $label = (array_key_exists('label', $recent)) ? $recent['label'] : '';
            $cont = $label.$recent['open'].$recent['content'].$recent['close'];
            unset($this->stack[count($this->stack)-1]);
        }
        else
        {
            $num = count($content);
            $total = count($this->stack);
            $start = ($total - $num);
            
            for($i=$start; $i < $total; $i++)
            {
                $recent = $this->stack[$i];
                $label = (array_key_exists('label', $recent)) ? $recent['label'] : '';
                $cont .= $label.$recent['open'].$recent['content'].$recent['close'];
                unset($this->stack[$i]);
            }
            
        }
        
        $this->resetStack();
        $el = $this->parseElement($element, $cont);
        
        $item['action'] = 'nest';
        $item['open']   = $el['open_tag'];
        $item['content'] = $el['content'];
        $item['close'] = $el['close_tag'];
        
        $this->stack($item);
        
        
        return $this;
    }
    
    
    public function parse( $data = array() )
    {
        $string = '';
        
        $data = (empty($data)) ? $this->stack : $data;
        
        foreach($data as $s)
        {
            
            $element = new \Library\HTML\Element();
            
            $parsed = $element->parse($s);
            
            $content = $s['content'];
            $string .= $parsed['open'];
            if($parsed['self_close'])
            {
                $string .= $parsed['close']."\n";
            }
            if(is_array($content))
            {
                $string .= $this->parse($content);
            }
            else
            {
                $string .= $parsed['content'];
            }
            if(!$parsed['self_close'])
            {
                $string .= $parsed['close']."\n";
            }
        }
 
        return $string;
        
    }
    
    public function debug($dump = false)
    {
        if(!$dump)
        {
            print_r($this->stack);
        }
        else
        {
            var_dump($this->stack);
        }
        
        return $this;
    }
    
    public function close()
    {
        $this->stack = array();
        return $this;
    }

    private function stack($item)
    {
        $this->stack[] = $item;
    }

    
    private function resetStack()
    {
        $stack = array();
        foreach($this->stack as $row)
        {
            $stack[] = $row;
        }
        
        $this->stack = $stack;
        
    }
    
    public function generate()
    {
        return $this->structure;
    }
    
}
