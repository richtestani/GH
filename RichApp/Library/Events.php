<?php
	
namespace RichApp\Library;

trait Events {

	/*
	*	general processing
	*/
	public function beforeProcess($data=array())
	{
		return false;
	}
	
	public function afterProcess($data=array())
	{
		return false;
	}
	
	/*
	* Used with CRUD Controller in the Admin
	*/
	public function beforeSave($data=array())
	{
		return false;
	}
	public function afterSave($data=array())
	{
		return false;
	}
	
	/*
	* Used for extending forms
	*/
	public function beforeForm($data=array())
	{
		return $data;
	}
	
	public function afterForm($data=array())
	{
		return $data;
	}
	
	public function beforeDelete($data=array())
	{
		return $data;
	}
	
	public function afterDelete($data=array())
	{
		return $data;
	}
	
}
	
?>