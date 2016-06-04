<?php
/*
 * RichApp: An easy way to build websites.
 */

define('DS', DIRECTORY_SEPARATOR);
define('PUBPATH', __DIR__);
define('APPPATH', realpath('../') . DS . 'RichApp');
define('APPROOT', realpath('../'));
define('EXT', '.php');
define('DEBUG', true);

require_once( APPPATH . DS . 'boot' . EXT );