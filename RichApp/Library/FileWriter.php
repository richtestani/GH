<?php
namespace Library;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class FileWriter {
    
    protected $server_path;
    protected $file_path;
    protected $file_name;
    protected $format;
    
    public function __construct()
    {
        $this->file_name = 'file';
        $this->format = 'txt';
        $this->server_path = $_SERVER['DOCUMENT_ROOT'];
    }
    
    public function setServerPath($path)
    {
        $this->server_path = $path;
    }
    
    public function setPath($path)
    {
        $this->file_path = $path;
    }
    
    public function setFileName($name)
    {
        $this->file_name = $name;
    }
    
    public function setFormat($format)
    {
        $this->format = $format;
    }
    
    public function write($text)
    {
        //open filename
        $fh = fopen($this->server_path.$this->file_path.DS.$this->file_name.'.'.$this->format, 'w');
        //send data to format
        fwrite($fh, $text);
    }
    
    public function emptyFile()
    {
        //open filename
        $fh = fopen($this->server_path.$this->file_path.DS.$this->file_name.'.'.$this->format, 'w');
        //send data to format
        fwrite($fh, '');
    }
}