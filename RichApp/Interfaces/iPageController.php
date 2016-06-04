<?php
namespace RichApp\Interfaces;

interface iPageController {
	public function index($page=1);
	public function create();
	public function edit($id=null);
	public function delete($id=null);
	public function setPageType($type);
}

?>