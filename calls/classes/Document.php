<?php

/**
 * Document
 * 
 * Documents Class for handling all document related functions
 * 
 * @package HDI
 * @author HDI
 * @copyright 2013
 * @version $Id$
 * @access public
 */
Class Document
{
    // Init.
    private $database_obj      = NULL;
    private static $table_name = 'documents';
    
    public $document_id, $patient_id, $int_profile_id, $ext_profile_id,
           $filename, $type, $size, $path, $date_created, $date_modified;
    
    
    /**
     * Document::__construct()
     * 
     * @param mixed $arr
     * @return void
     */
    public function __construct($arr=array())
    {
        $this->database_obj   = Database::obj();
        
        $this->document_id    = isset($arr['document_id']) ? $arr['document_id']: '';
        $this->patient_id     = isset($arr['patient_id']) ? $arr['patient_id']: '';
        $this->int_profile_id = isset($arr['int_profile_id']) ? $arr['int_profile_id']: '';
        $this->ext_profile_id = isset($arr['ext_profile_id']) ? $arr['ext_profile_id']: '';
        $this->filename       = isset($arr['filename']) ? $arr['filename']: '';
        $this->type           = isset($arr['type']) ? $arr['type']: '';
        $this->size           = isset($arr['size']) ? $arr['size']: '';
        $this->path           = isset($arr['path']) ? $arr['path']: '';
        $this->date_created   = isset($arr['date_created']) ? $arr['date_created']: '';
        $this->date_modified  = isset($arr['date_modified']) ? $arr['date_modified']: '';
    }    
    
    /**
     * Documents::query()
     * 
     * Executes a specified query
     *      
     * @param mixed $args
     * @return
     */
    public function query($args=array())
    {
        // SQL
        $sql    = (isset($args['select'])) ? " SELECT {$args['select']} "  : '';
        $sql   .= (isset($args['from']))   ? " FROM {$args['from']} "      : '';
        $sql   .= (isset($args['where']))  ? " WHERE {$args['where']} "    : '';
        $sql   .= (isset($args['group']))  ? " GROUP BY {$args['group']} " : '';
        $sql   .= (isset($args['order']))  ? " ORDER BY {$args['order']} " : '';
        $sql   .= (isset($args['limit']))  ? " LIMIT {$args['limit']} "    : '';
        
        // Format
        $format = (isset($args['format'])) ? $args['format'] : 'Object';
        
        // Return
        return $this->database_obj->execute_query($sql,$format);
    }
    
    /**
     * Documents::checkUploadErrors()
     * 
     * Checks file upload errors and returns a string representing an error code
     * 
     * @param mixed $error
     * @return
     */
    private function check_upload_errors($error) 
    { 
        $message = "";
        switch ($error) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        return $message; 
    } 
    
    /**
     * Documents::deleteFile()
     * 
     * Deletes a specified file from memmory and returns a boolean indicating whether the file was deleted or not
     * 
     * @return
     */
    public function delete_file()
    {
        return @unlink($file->file);
    }
    
    /**
     * Documents::insert_document()
     * 
     * Returns last inserted id          
     * 
     * @return
     */                      
    public function save_document()
    {                  
            // SQL
            $sql = " INSERT INTO ".self::$table_name." (patient_id, filename, type, size, path, date_created, date_modified)
                     VALUES (:patient_id, :filename, :type, :size, :path, :date_created, :date_modified) ";
            
            // Bind
            $bind_array = array(
                ':patient_id'     => array($this->patient_id, PDO::PARAM_STR),
                ':filename'       => array($this->filename, PDO::PARAM_STR),
                ':type'           => array($this->type, PDO::PARAM_STR),
                ':size'           => array($this->size, PDO::PARAM_INT),
                ':path'           => array($this->path, PDO::PARAM_STR),
                ':date_created'   => array($this->date_created, PDO::PARAM_STR),
                ':date_modified'  => array($this->date_modified, PDO::PARAM_STR)
            );
            
            // Execute
            return  $this->database_obj->execute_query($sql,'',$bind_array);
            
            /*if ($is_inserted)
            {
                return $this->database_obj->last_insert_id();
            }*/ 
        /*} else {
            
            // SQL
            $sql = " UPDATE ".self::$table_name." 
                    filename              = :filename,
                    patient_id            = :patient_id,
                    int_profile_id        = :int_profile_id,
                    ext_profile_id        = :int_profile_id,
                    type                  = :type, 
                    size                  = :size, 
                    path                  = path, 
                    date_created          = date_created, 
                    date_modified         = date_modified 
                    
                    WHERE patient_id      = :patient_id";
            $is_inserted = $this->database_obj->execute_query($sql,'',$bind_array);
            
            if ($is_inserted)
            {
                return $this->database_obj->last_insert_id();
            } 
            } else {
                
                // SQL
                $sql = " UPDATE ".self::$table_name." 
                        filename       = :filename,
                        patient_id     = :patient_id,
                        int_profile_id = :int_profile_id,
                        ext_profile_id = :int_profile_id,
                        type           = :type, 
                        size           = :size, 
                        path           = path, 
                        created        = created, 
                        modified       = modified ";
                
                // Variables
                $filename = isset($doc['filename'])   ? trim($doc['filename'])   : '';
                $type     = isset($doc['type'])       ? trim($doc['type'])       : '';
                $size     = isset($doc['size'])       ? trim($doc['size'])       : '';
                $path     = isset($doc['thumb_path']) ? trim($doc['thumb_path']) : '';
                $created  = isset($doc['created'])    ? trim($doc['created'])    : '';
                $modified = isset($doc['modified'])   ? trim($doc['modified'])   : '';
                
                // Bind
                $bind_array = array(
                    ':filename'        => array($filename, PDO::PARAM_STR),
                    ':type'            => array($type, PDO::PARAM_STR),
                    ':size'            => array($size, PDO::PARAM_INT),
                    ':path'            => array($path, PDO::PARAM_STR),
                    ':created'         => array($created, PDO::PARAM_STR),
                    ':modified'        => array($modified, PDO::PARAM_STR)
                );
            }*/                   
        //}
    }
} 
?>
