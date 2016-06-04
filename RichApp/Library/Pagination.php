<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library;

/**
 * Description of Pagination
 *
 * @author richardtestani
 */
class Pagination {
    //put your code here
    protected $total_num_pages;
    protected $num_pages_shown;
    protected $current_page;
    protected $page_num_template ='';
	protected $page_class;
	protected $base_page_link;
	protected $base_link;
	
	public function __construct()
	{
		$this->base_link = '/';
		$this->base_page_link = 'page/';
		$this->current_page = 1;
		$this->setPageClass('page');
	}
	
	public function setBasePageLink($link)
	{
		$this->base_page_link = $link.DS."%d";
		return $this;
	}
	
	public function setBaseLink($link)
	{
		$this->base_link = $link;
		return $this;
	}
    
    public function currentPageNum($page)
    {
        $this->current_page = $page;
        return $this;
    }
    
    public function setNumPagesShown($num)
    {
        $this->num_pages_shown = $num;
		return $this;
    }
    
    public function setPageNumTemplate($template)
    {
        $this->page_num_template = $template;
    }
    
    public function setTotalNumPages($rows, $rowsPerPage)
    {
		$pages = ceil($rows/$rowsPerPage);
        $this->total_num_pages = $pages;
		return $this;
    }
	
	public function setPageClass($class)
	{
		$this->page_class = $class."%s";
	}
    
    public function get()
    {
		$string = '';
		$link = $this->base_link.DS.$this->base_page_link.DS;
		$template = '<span class="'.$this->page_class.'"><a href="'.$link.'" class="hvr-fade">%d</a></span>';
		$this->page_num_template = $template;
        if($this->total_num_pages < $this->num_pages_shown)
        {
            $loop = $this->total_num_pages;
        }
        else
        {
            $loop = $this->num_pages_shown;
        }
        
        for($i=0; $i<$loop; $i++)
        {
			$page_num = $i+1;
			$cur_page = $this->current_page;
			$active = ($cur_page == $page_num) ? ' active' : '';
			
            $string .= sprintf($this->page_num_template,$active, $page_num, $page_num);
        }
        return '<div id="pagination">'.$string.'</div>';
    }
}
