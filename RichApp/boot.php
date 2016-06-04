<?php

/* 
 * Author: Richard Testani
 * Designed For RLTCMS
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 * 
 * loads the required files, sets up app configuration
 * themes are set, views are set and the app is run
 */

session_start();

$loader     = include APPPATH . DS . 'vendor/autoload.php';
$setup      = false;
$config     = array();

if( file_exists( APPPATH . DS . 'RAConfig.php' ) )
{
    $config = require_once APPPATH . DS . 'RAConfig.php';
    $setup = true;
}

$app = new \RichApp\Core\App($setup, $config);