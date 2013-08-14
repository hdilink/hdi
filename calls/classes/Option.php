<?php
class Option
{
    private $database_obj      = NULL;
    private static $table_name = 'options';
    
    public function __construct()
    {
        $this->database_obj = Database::obj();
    }
    
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
    
    public function get_option($option_name)
    {
        // Fetch the existing value if any
        $args = $this->query(array(
            'select' => 'option_value',
            'from'   => 'options',
            'where'  => "option_name='$option_name'",
            'limit'  => '1',
            'format' => 'Array'
        ));
        
        // Return
        if (!empty($args))
        {
            return $args[0]['option_value'];
        }
    }
    
    public function next_code($option_name)
    {
        // Start transaction
        $this->database_obj->begin_transaction();
        
        // Init.
        $option_value = 1;
        $opt_obj      = '';
        
        // Fetch the existing value if any
        $opt_obj = $this->query(array(
            'select' => 'option_value',
            'from'   => 'options',
            'where'  => "option_name='$option_name'",
            'limit'  => '1',
            'format' => 'Array'
        ));
        
        // Found a match
        if (!empty($opt_obj))
        {
            // Increment the counter
            $option_value = $opt_obj[0]['option_value'] + 1;
            
            // SQL
            $sql2 = " UPDATE options
                      SET option_value=:option_value
                      WHERE option_name='$option_name' ";
            
            // Bind
            $bind_array = array(
                ':option_value' => array($option_value, PDO::PARAM_STR)
            );
            
            // Execute
            $update_msg = $this->database_obj->execute_query($sql2,'',$bind_array);
            
            // Transaction status
            if ($update_msg === true)
            {
                // Commit transaction
                $this->database_obj->commit_transaction();
            }
            else
            {
                // Decrement the counter
                $option_value--;
                
                // Roll back transaction
                $this->database_obj->roll_back();
            }
        }
        // No match was found
        else
        {
            // SQL
            $sql = " INSERT INTO options (option_name, option_value)
                     VALUES (:option_name, :option_value) ";
            
            // Bind
            $bind_array = array(
                ':option_name'  => array($option_name, PDO::PARAM_STR),
                ':option_value' => array($option_value, PDO::PARAM_STR)
            );
            
            // Execute
            $insert_msg = $this->database_obj->execute_query($sql,'',$bind_array);
            
            // Transaction status
            if ($insert_msg === true)
            {
                // Commit transaction
                $this->database_obj->commit_transaction();
            }
            else
            {
                // Empty the counter
                $option_value = '';
                
                // Roll back transaction
                $this->database_obj->roll_back();
            }
        }
        
        // Return
        return $option_value;
    }
    
    public function dropdown_list($group_name)
    {
        if('' != $group_name)
        {
            $query = $this->query(array(
                'select' => "option_name as name, option_id as id",
                'from'   => self::$table_name,
                'order'  => 'option_name',
                'where'  => "group_name='$group_name'",
                'format' => 'Array'
            ));
            
            // Return
            return $query;
        }
    }
} ?>