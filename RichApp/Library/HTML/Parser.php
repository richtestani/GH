<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Library\HTML;

/**
 * Description of Parser
 *
 * @author richardtestani
 */
class Parser {
    
    protected $data;
    
    public function __construct($string='')
    {
        
        if(!empty($string))
        {
            return $this->parseHTML($string);
        }
        
    }
    
    public function parseHTML($string)
    {
        
        /*
         * find the tag
         */
        $pattern = '/(?<tag>^[a-z0-6]+)/';
        preg_match($pattern, $string, $tags);
        $this->data['tag'] = (array_key_exists('tag', $tags)) ? $tags['tag'] : '';
        
        /*
         * find an id
         */
        $pattern = '/(?<id>(#)[^.:{}|!#*&=+\[\]]+)/';
        preg_match($pattern, $string, $ids);

        $this->data['id'] = (array_key_exists('id', $ids)) ? substr($ids['id'], 1, strlen($ids['id'])) : '';
        
        
        /*
         * find the classes
         */
        $pattern = '/(?<class>[.][^#:@&=+\[\]\{\}>\"]+)/';
        preg_match($pattern, $string, $class);
        $this->data['class'] = (array_key_exists('class', $class)) ? trim(implode(' ', explode('.', $class['class']))) : '';
        
        /*
         * check for a value
         */
        $pattern = '/(?<value>["][\W\w]+["])/uis';
        preg_match($pattern, $string, $value);
        
        $this->data['value'] = (array_key_exists('value', $value)) ? rtrim(trim($value['value'], '"'), '"') : '';
        
        
        /*
         * find attributes
         */
        $pattern = '/(?<atts>[\[]{2}[\w=\/\-\d\.\&\s]+[\]]{2})/uis';
        preg_match($pattern, $string, $atts);
        
        $a = (array_key_exists('atts', $atts)) ? explode('&', trim($atts['atts'], '[]')) : array();
        $attributes = '';
         foreach($a as $b)
         {
             $pair = explode('=', $b);
             $attributes .= $pair[0];
             $attributes .= (array_key_exists(1, $pair)) ? '="'.$pair[1].'" ' : '';
         }
         
         $this->data['attributes'] = $attributes;
        
        /*
         * find links and srcs
         */
        $pattern = '/(?<link>[:]{2}[^\s]+)/uis';
        preg_match($pattern, $string, $links);
        
        $this->data['link'] = (array_key_exists('link', $links)) ? substr($links['link'], 2, strlen($links['link'])) : '';
        
        return $this->data;
        
    }
}
