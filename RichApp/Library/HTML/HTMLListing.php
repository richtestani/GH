<?php

/*
 * Easily build listings in HTML
 * 
 */

namespace RichApp\Library\HTML;

/**
 * Description of HTMLListing
 *
 * @author richardtestani
 */
class HTMLListing implements iHTMLWidgetable {
    
    protected $data;
    protected $listing;
    protected $template;
    protected $transformers = array();
    protected $page_name;
    
    public function __construct($data = array())
    {
        if(!empty($data))
        {
            $this->setData($data);
        }
    }
    
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    public function setLineTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
    
    public function build()
    {
        $data = $this->data;
        $listing = '';
        
        //headers
        $header = '';
        $template = $this->template;
        foreach($this->columns as $h)
        {
            $pattern = "(([{]{2})([$h]+)([}]{2}))";
            preg_match($pattern, $template, $match);
            if(!empty($match))
            {
                $key = $match[0];
                $h = str_replace('_', ' ', $h);
                $template = str_replace($key, $h, $template);
            }
        }
        
        $header = '<div id="ra-listing-header">'.$template.'</div>';
        //  build main data
        $line = array();
        foreach($data as $record)
        {
            $template = $this->template;
            
            foreach($record as $k => $v)
            {
                if($k == 'id')
                {
                    //edit
                    $pattern = "(([{]{2})([edit]+)([}]{2}))";
                    preg_match($pattern, $template, $match);
                    if(!empty($match))
                    {
                        $key = $match[0];
                        $edit = '<a href="/admin/'.$this->page_name.'/edit/'.$v.'"><span class="fa fa-edit">edit</span></a>';
                        $template = str_replace($key, $edit, $template);
                    }
                    
                    //delete
                    $pattern = "(([{]{2})([delete]+)([}]{2}))";
                    preg_match($pattern, $template, $match);
                    if(!empty($match))
                    {
                        $key = $match[0];
                        $delete = '<a href="/admin/'.$this->page_name.'/delete/'.$v.'"><span class="fa fa-delete">delete</span></a>';
                        $template = str_replace($key, $delete, $template);
                    }
                }
                if(in_array($k, $this->columns))
                {
                    
                    if(array_key_exists($k, $this->transformers))
                    {
                        $transform = $this->transformers[$k];
                        extract($transform);
                        if(!isset($method))
                        {
                            return 'No method applied';
                        }
                        $method = 'transform'.ucwords($method);
                        $transformed = $this->$method($v, $args);
                        $v = $transformed;
                    }
                    $pattern = "(([{]{2})([$k]+)([}]{2}))";
                    preg_match($pattern, $template, $match);
                    if(!empty($match))
                    {
                        $key = $match[0];
                        $template = str_replace($key, $v, $template);
                    }
                }
                
            }
            $line[] = $template;
        }
        $listing = '<div id="ra-listing">'.$header.'<div id="ra-listing-body">'.implode('', $line).'</div></div>';
        return $listing;
    }
    
    public function setPageName($page)
    {
        $this->page_name = $page;
        return $this;
    }
    
    public function transformColumn($column, $transformation)
    {
        $this->transformers[$column] = $transformation;
        return $this;
    }
    
    public function transformDate($value, $args)
    {
        $datestr = strtotime($value);

        extract($args);
        $date = date($format, $datestr);
        return $date;
    }
    
    public function setColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }
}
