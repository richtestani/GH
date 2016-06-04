<?php
namespace RichApp\Library;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Upload {
    
    protected $filename;
    protected $file_ext;
    protected $original_filename;
    protected $types = array();
    protected $random_filename;
    protected $files_name;
    protected $upload_path;
    protected $upload_data;
    protected $mime_type;
    protected $public_path;
    
    public function __construct($name = 'uploads')
    {
        //default file types
        $this->types = array(
            //
            //Images
            'image/png' => 'png',
            'image/jpg' => 'jpg',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            //
            //Text
            'text/plain' => 'txt',
            'text/css' => 'css',
            'text/html' => 'html',
            
            //
            //multimedia
            'application/pdf' => 'pdf',
            'application/ms-word' => 'doc'
            
            );
        
        $this->random_filename = false;
        $this->files_name = $name;

    }
    
    public function setFilesName($name)
    {
        $this->files_name = $name;
    }
    
    public function setFileType($mime)
    {
        if( array_key_exists($mime, $this->types) )
        {
            $this->file_ext = $this->types[$mime];
            $this->mime_type = $mime;
        }
    }
    
    public function addFileType($mime, $ext)
    {
        $this->types[$mime] = $ext;
    }
    
    public function useRandomFileName($random = true)
    {
        $this->random_filename = $random;
    }
    
    public function setUploadPath($path)
    {
        $this->upload_path = rtrim($path, "/");
        $this->setPublicPath($this->upload_path);
    }
    
    public function setPublicPath($path)
    {
        $this->public_path =  trim($path, "/");
    }
    
    public function uploadFile($destination = '')
    {
        if(!empty($destination))
        {
            $this->upload_path = rtrim($destination, "/");
        }
		
        $files = $_FILES[$this->files_name];
        $counter = 0;
        $num_images = count($_FILES);
        $errors = array();
		
        if(!file_exists($this->upload_path))
        {
            mkdir($this->upload_path, 0775, true);
        }
        foreach($files['error'] as $error)
        {
            
            if($error[$counter] == UPLOAD_ERR_OK)
            {
                
                $this->setFileType($files['type'][$counter]);
                $this->upload_data[$counter]['original_name'] = $files['name'][$counter];
                $this->upload_data[$counter]['tmp_name'] = $files['tmp_name'][$counter];
                $this->upload_data[$counter]['file_size'] = $files['size'][$counter];
                $this->upload_data[$counter]['mime_type'] = $this->mime_type;
                $this->upload_data[$counter]['file_ext'] = $this->file_ext;
                $this->upload_data[$counter]['upload_path'] = $this->upload_path;
                $this->upload_data[$counter]['use_random'] = ($this->random_filename) ? 'yes' : 'no';
                $this->upload_data[$counter]['public_path'] = $this->public_path;
                if($this->random_filename)
                {
                    $this->generateRandomName($files['name'][$counter]);
                }
                else
                {
                    $this->filename = $files['name'][$counter];
                }
                
                $this->upload_data[$counter]['filename'] = $this->filename;
                move_uploaded_file( $files["tmp_name"][$counter], $this->upload_path . DS . $this->filename);
                $counter++;
            }
            else
            {
                $errors[] = $error[$counter];
            }
        }
        
        if(!empty($errors))
        {
            return false;
        }
        else
        {
            return $this->upload_data;
        }

    }
    
    private function generateRandomName($filename)
    {
        $hash = md5($filename);
        $this->filename = $hash;
        
    }
    
}