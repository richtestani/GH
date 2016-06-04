<?php

//add your routes
$this->app->get('/admin/marketplace/:controller(/)', function($controller){
	$controller = 'Marketplace\Controller\Admin\\'.ucwords($controller);
	$Controller = new $controller();
	$Controller->index();
});

$this->app->get('/admin/marketplace/:controller/:method(/:id)', function($controller, $method, $id=0){
	$controller = 'Marketplace\Controller\Admin\\'.ucwords($controller);
	$Controller = new $controller();
	$Controller->$method($id);
});

$this->app->post('/admin/marketplace/:controller/save(/:id)(/)', function($controller, $id=0){
	$controller = 'Marketplace\Controller\Admin\\'.ucwords($controller);
	$Controller = new $controller();
	$Controller->save($id);
});

$this->app->get('/admin/marketplace/:controller(/)', function($controller){
	$controller = 'Marketplace\Controller\Admin\\'.ucwords($controller);
	$Controller = new $controller();
	$Controller->index();
});

$this->app->get('/admin/marketplace/:controller/:method(/:id)(/)', function($controller, $method, $id=0){
	$controller = 'Marketplace\Controller\Admin\\'.ucwords($controller);
	$Controller = new $controller();
	$Controller->$method($id);
});

$this->app->post('/admin/marketplace/sellers/saveUser', function()
{
	$controller = 'Marketplace\Controller\Admin\\Sellers';
	$Controller = new $controller();
	$Controller->saveUser();
});

$this->app->post('/admin/marketplace/:controller/search(/:format)(/)', function($controller, $format=''){
	$controller = 'Marketplace\Controller\Admin\\'.ucwords($controller);
	$Controller = new $controller();
	$Controller->search($format);
});

//front end routes
$this->app->get('/', function(){
	$controller = new \Marketplace\Controller\Home();
	$controller->index();
});
$this->app->get('/listing/:slug', function($slug){
	$Controller = new \Marketplace\Controller\Listings();
	$Controller->single($slug);
});
$this->app->get('/media/:type/:slug', function($type, $slug){
	$Controller = new \Marketplace\Controller\Media();
	$Controller->$type($slug);
});
$this->app->get('/topic/:topic(/)', function($topic){
	$Controller = new \Marketplace\Controller\Topics();
	$Controller->index($topic);
});
$this->app->get('/user/register', function(){
	$Controller = new \Marketplace\Controller\Sellers();
	$Controller->register();
});
$this->app->get('/user/login', function(){
	$Controller = new \Marketplace\Controller\Sellers();
	$Controller->login();
});
$this->app->get('/user/(profile/):username', function($username){
	$Controller = new \Marketplace\Controller\Sellers();
	$Controller->profile($username);
});
$this->app->get('/cart', function(){
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->index();
});
$this->app->post('/cart/add', function()
{
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->add();
});
$this->app->post('/cart/update', function()
{
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->update();
});
$this->app->get('/cart/remove/:id', function($id)
{
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->remove($id);
});
$this->app->get('/cart/checkout', function()
{
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->checkout();
});
$this->app->post('/cart/checkout', function()
{
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->process();
});
$this->app->get('/cart/empty', function()
{
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->emptyCart();
});
$this->app->get('/cart/thanks', function()
{
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->thanks();
});
$this->app->get('/cart/logout', function()
{
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->logout();
});
$this->app->get('/cart/connect(/auth/)', function()
{ 
	$Controller = new \Marketplace\Controller\Cart();
	$Controller->connect();
});