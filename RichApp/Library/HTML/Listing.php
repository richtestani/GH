<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Library\HTML;

/**
 * Description of Listing
 *
 * @author richardtestani
 */
class Listing extends \Library\HTML\Structure {
    
    protected $edit;
    protected $delete;
    protected $show_column_names;
    protected $columns = array();
	protected $additional_columns = array();
	protected $hidden_columns = array();
    protected $cols;
    protected $num_cols;
    protected $edit_link;
    protected $delete_link;
    protected $link_vars = array();
	protected $callback = null;
	protected $row_template;
	protected $item_template;
	protected $template_variables = array();
	protected $transformations = array();
    static protected $counter = 1;
    
    public function __construct($model)
	{
		$this->model = $model;
	}
	
	public function setModel($model)
	{
		$this->model = $model;
	}
	
    public function setEditLink($link, $field)
    {
        $this->additional_columns[] = 'edit';
        $this->link_vars[] = $field;
        $this->edit_link = $link;
        return $this;
    }
    
    public function setDeleteLink($link, $field)
    {
        $this->additional_columns[] = 'delete';
        $this->link_vars[] = $field;
        $this->delete_link = $link;
        return $this;
    }
    
    public function setNumColumns($num)
    {
        $this->num_cols = $num;
        return $this;
    }
	
	public function setRowTemplate($row, $item, $show_column_names = true)
	{
		$this->row_template = $row;
		$this->item_template = $item;
		$this->show_column_names = $show_column_names;
		return $this;
		
	}
	
	public function setRow($template)
	{
		$this->row_template = $template;
		return $this;
	}
	
	public function setTemplateVariables($var)
	{
		$this->template_variables[] = $var;
		return $this;
	}
	
	public function generate($listing_template = 'div#listing')
	{
		$method = (!is_null($this->callback)) ? $this->callback : 'findAllFrom';
		$records = $this->model->$method($this->callback_args);
		
		$this->setRows($records, $this->item_template, $this->show_column_names, $listing_template);
		return $this;
	}
	
	public function setCallback($callback = null, $args = null)
	{
		$this->callback = $callback;
		$this->callback_args = $args;
		return $this;
	}
    
    public function setRows($data, $line, $use_first_row = true, $listing_template = 'div#listing')
    {
 	   
        $rows = array();
		$header = array();
		if($use_first_row)
		{
			$cols = array_merge($this->columns, $this->additional_columns);
            $num_cols = count($cols);
            $class = 'col-lg-'.round((12/$num_cols)).' item record header';
			foreach($cols as $c) {
				$name = str_replace(' ', '-', strtolower($c));
				$id = '#header-'.$name;
	            if(!in_array($c, $this->hidden_columns))
				{
					$header[] = array('span'.$id.'.'.$class, $c);
				}
			}
			$header = $this->block($this->row_template, $header)->parse();
		}
		$trans = array_merge(array(), array_keys($this->transformations));
		
        foreach($data as $row)
        {
            $num_cols = count($row);
            $class = 'col-lg-'.round((12/$num_cols));
            $lines = array();
            $counter = 0;

			
            foreach($row as $k => $v)
            {
				if(!in_array($k, $this->columns))
				{
					continue;
				}
				
                if(in_array($k, $this->link_vars))
                {
                    $edit_link = str_replace('{{'.$k.'}}',  $v, $this->edit_link);
                    $delete_link = str_replace('{{'.$k.'}}',  $v, $this->delete_link);
                    $edit = $line.'#edit-'.self::$counter.'>a::'.$edit_link;
                    $delete = $line.'.delete.#delete-'.self::$counter.'>a::'.$delete_link;
                }

                if(!in_array($k, $trans))
				{
	                $id = $k.'-'.self::$counter;
	                $element = $line.'#'.$id.'[[data-field-'.$k.'='.$v.']]';
	                $content = $v;
					if(!in_array($k, $this->hidden_columns)) 
					{
						$lines[] = array($element, $content);
					}
	                
				}
				else
				{
					$t = $this->transformations[$k];
					$type = $t['type'];
					$label = $t['label'];
					$template = $t['template'];
	                //$id = $k.'-'.self::$counter;
	                //$element = $line.'#'.$id.'[[data-field-'.$k.'='.$v.']]';
					$data_field = 'data-field-'.$k;
					$data = array(
						'id' => $k.'-'.self::$counter,
						$data_field => $v,
						'value' => $v,
						'name' => $k,
						'class' => 'listing-small'
					);
					$element = $this->transformField($type, $data, $label, $template='');
					
	                //$content = $v;
		            if(!in_array($k, $this->hidden_columns))
					{
						$lines[] = array($element, $label);
					}
	                
				}
                
                $counter++;
            }
            
            
            if(in_array('edit', $this->additional_columns))
            {
                $lines[] = array($edit, 'Edit');
            }
            
            if(in_array('delete', $this->additional_columns))
            {
                $lines[] = array($delete, 'Delete');
            }
            
			if(in_array($k, $this->hidden_columns))
			{
				//$lines[] = array('', '');
			}
			$row_temp = $this->parseTemplateVariables($this->row_template, $row);
            $rows[] = $this->block($row_temp, $lines)->parse();
            $this->close();
            self::$counter++;
        }
		
        $rows = implode("", $rows);
        $html = $this->close()->wrap($listing_template, $rows)->parse();
        return $this;
    }
    
    private function transformField($type, $data, $label, $template)
    {
		
        switch($type)
		{
			case 'checkbox':
			$att = '';
			foreach($data as $k => $v)
			{
				$att .= $k.'='.$v.'&';
				if($k == 'value')
				{
					if($v == 1 AND !empty($v))
					{
						$att .= 'checked=checked&';
					}
				}
			}
			$att .= '&data-label-text='.$data['name'];
			$att = rtrim($att, '&');
			$str = $this->item_template.'>input'.$template.'[[type=checkbox&'.$att.']]';
			return $str;
			break;
			
			case 'date_format':
				
				$str = $this->item_template.'>span'.$template.'[[data-date-format=mysql]]"test"';
				return $str;
			break;
		}
    }
	
	public function transformColumn($field, $type, $label='', $template = '')
	{
		$this->transformations[$field] = array('field'=>$field, 'type'=>$type, 'label'=>$label, 'template' => $template);
		return $this;
	}
    
    public function setColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }
    
    public function hideColumn($column)
    {
		$this->hidden_columns[] = $column;
        return $this;
    }
	
	private function parseTemplateVariables($data, $val)
	{
		foreach($this->template_variables as $tv)
		{
			if(is_array($val) AND array_key_exists($tv, $val))
			{
				$data = str_replace('{{'.$tv.'}}', $val[$tv], $data);
			}
		}
		
		return $data;
	}

    
}
