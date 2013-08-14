<?php
class Int_Profile
{
    private $database_obj = NULL;
    
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
    
    public function dropdown_list($option_name)
    {
        if('' != $option_name)
        {
            // SQL
            $sql = "SELECT CONCAT(o.option_name,' ',i.surname,', ',i.first_name) as name, i.int_profile_id as id
                    FROM int_profiles i
                    INNER JOIN options o
                    ON o.option_id=i.title
                    WHERE i.user_id
                    IN (SELECT id FROM users WHERE user_type = (SELECT option_id FROM options WHERE option_name='{$option_name}' AND group_name='user'))
                    ORDER BY i.surname";
        
            // Execute
            return $this->database_obj->execute_query($sql,'Array');
        }
    }

} ?>

