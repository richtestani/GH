<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Interfaces;

/**
 *
 * @author richardtestani
 */
interface iModeler {
    
    public function create($data);
    
    public function read();
    
    public function update($data, $id);
    
    public function delete($id);
	
	public function getProperties();
    
}
