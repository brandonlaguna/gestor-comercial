<?php
class UploadFile Extends EntidadBase{


    private $file;
    private $filename;
    private $filetype;
    private $filesize;
    private $source;
    private $path;
    private $validextensions;

    public function __construct($adapter) {
        $table ="";
        parent:: __construct($table, $adapter);
    }

    public function getFile()
    {
        return $this->file;
    }
    public function setFile($file)
    {
        $this->file = $file;
    }
    public function getFilename()
    {
        return $this->filename;
    }
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
    public function getFiletype()
    {
        return $this->filetype;
    }
    public function setFiletype($filetype)
    {
        $this->filetype = $filetype;
    }
    public function getFilesize()
    {
        return $this->filesize;
    }
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;
    }
    public function getSource()
    {
        return $this->source;
    }
    public function setSource($source)
    {
        $this->source = $source;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function setPath($path)
    {
        $this->path = $path;
    }
    public function getValidextensions()
    {
        return $this->validextensions;
    }
    public function setValidextensions($validextensions)
    {
        $this->valid_extensions = $validextensions;
    }

    public function import()
    {
        
        if($this->source){
            
            $actual_file_size = $this->filesize / 1024;
            $tmp = $this->source;
            $data = file_get_contents($tmp);
            $base64 = 'data:image/' . $this->filetype . ';base64,' . base64_encode($data);
            // Upload file
            $final_image = $this->filename;
            $actual_date= date("Y-m-d H:i:s");
            if(in_array($this->filetype, $this->validextensions)) {   
                if (!is_dir($this->path)) {
                    mkdir($this->path, 0777, true);
                }
                $path2 = $this->path."/".strtolower($final_image); 
                $rel_path = strtolower($final_image); 
                $filesize =filesize($tmp);
                $actual_file_size = $filesize / 1024;
                //verificar si la cuenta esta configurada para guardar en base64
                $base64_config = false;
                if($base64_config){
                    $pic_route = $base64;
                }else{
                    move_uploaded_file($tmp,$path2);
                    $pic_route = "/".$rel_path;
                }

            }
        } 
    }
    
    
}