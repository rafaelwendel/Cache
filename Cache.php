<?php
/**
 * A helper for crete Cache Files
 *
 * @author Rafael Wendel Pinheiro
 * @version 1.0
 * @link https://github.com/rafaelwendel/Cache
 */
class Cache {
    
    protected $cache_dir = '';
    protected $errors = array();
    
    public function __construct($cache_dir = '') {
        if ($cache_dir != ''){
            $this->set_cache_dir($cache_dir);
        }            
    }
    
    public function set_cache_dir($cache_dir){
        if (substr($cache_dir, 0, strlen($cache_dir) - 1) == '/'){
            $cache_dir = substr($cache_dir, 0, strlen($cache_dir) - 1);
        }
        
        if (file_exists($cache_dir)){
            $this->cache_dir = $cache_dir;
        }
        else{
            $this->set_error('Cache folder not found');
        }
    }
    
    protected function set_error($error){
        $this->errors[] = $error;
    }
    
    public function get_errors(){
        return $this->errors;
    }
    
    public function get_file_path($file_name){
        return $this->cache_dir . '/' . $file_name . '.php';
    }
    
    public function create_folder($path){
        try{
            if(!file_exists($path)){
                mkdir($path);                
            }
            return true;
        }
        catch(Exception $e){
            $this->set_error($e->getMessage());
            return false;
        }
    }
    
    public function create_cache($name, $content, $overwrite = true){
        if (!$this->cache_dir){
            $this->set_error('The cache folder is not defined');
            return false;
        }
        
        $file_path = $this->cache_dir . '/' . $name . '.php';
        
        if ((file_exists($file_path) && $overwrite) || (!file_exists($file_path))){
            $pointer = fopen($file_path, 'w');
            if (!$pointer){
                $this->set_error('Error while start pointer');
                return false;
            }
            fwrite($pointer, $content);
            fclose($pointer);
            return true;
        }
        else{
            $this->set_error('The file ' . $file_path . ' already exists');
            return false;
        }
    }
    
    public function delete_cache($file_path){
        if ($this->cache_exists($file_path)){
            return unlink($file_path);
        }
        return false;
    }
    
    public function cache_exists($file_name){
        if (file_exists($this->get_file_path($file_name))){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function get_cache($file_name){
        if ($this->cache_exists($file_name)){
            return file_get_contents($this->get_file_path($file_name));
        }
        return false;
    }
}