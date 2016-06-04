<?php
namespace RichApp\Interfaces;

interface iCrud {
	public function create();
	public function edit($id=0);
	public function save($id=0, $callback=null);
	public function delete($id=0);
}
?>