<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Core;

/**
 * Description of Router
 *
 * @author richardtestani
 */
trait URLTrait {
    
    public function isAdmin()
    {
        $path = $this->request->getPath();
        return (strpos($path, "/admin") !== FALSE);
    }
    
    public function isInstaller()
    {
        $path = $this->request->getPath();
        return (strpos($path, "/installer") !== FALSE);
    }
	
	public function isLogin()
	{
        $path = $this->request->getPath();
        return (strpos($path, "/admin/login") !== FALSE);
	}
	
	public function setLinkBasePath($class='')
	{
		$url = $_SERVER['REQUEST_URI'];
		$urlParts = explode("/", $url);

		$settings = $this->system->get('app');
		$package = ( !in_array(strtolower($settings['package']), $urlParts) ) ? '' : $settings['package'].DS;
		$classpath = explode("\\", get_called_class());
		$class = $classpath[count($classpath)-1];
		$basepath = strtolower($package.$class);
		$this->setData('basepath', $basepath);
	}
	
	public function setRedirectLink($class='')
	{
		$url = $_SERVER['REQUEST_URI'];
		$urlParts = explode("/", $url);

		$settings = $this->system->get('app');
		$package = ( !in_array(strtolower($settings['package']), $urlParts) ) ? '' : $settings['package'].DS;
		$classpath = explode("\\", get_called_class());
		$class = $classpath[count($classpath)-1];
		$basepath = '/admin/'.strtolower($package.$class);

		$this->setData('redirectlink', $basepath);
	}
	
	public function hasSegment($segment)
	{
		$url = $_SERVER['REQUEST_URI'];
		$urlParts = explode("/", $url);
		
		return ( in_array($segment, $urlParts) ) ? true : false;
	}
}
