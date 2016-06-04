<?php

/*
 * Easily build record listings in HTML.
 * Record listings allow edit, delete options at the end of each row.
 * 
 * $line = '<div class="line"><span>{{id}}</span><span>{{name}}</span><span>{{value}}</span></div>';
 * $listing = new HTMLRecordListing($data);
 * $listing->setLinkReference('id');
 * 
 */

namespace Library\HTML;
use RichApp\HTML\HTMLListing;

/**
 * Description of HTMLRecordListing
 *
 * @author richardtestani
 */
class HTMLRecordListing extends HTMLListing {
    
    /* string */
    protected $reference;
    
    public function setLinkReference($reference)
    {
        $this->reference = $reference;
    }
    
    public function build()
    {
        $data = (array)$this->data;
    }
}
