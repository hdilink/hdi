<?php
class Int_Profile
{
    // Init.
    private $database_obj      = NULL;
    private $session_obj       = NULL;
    private $kinship_obj       = NULL;
    private static $table_name = 'int_profiles';
    
    public $int_profile_id, $user_id, $title, $first_name, $middle_name, $surname, $gender, $address, $email, 
           $phone_1, $phone_2, $date_of_birth, $marital_status, $religion, $qualification, $country, 
           $kin_first_name, $kin_middle_name, $kin_surname, $kin_gender, $kin_address, $kin_email, 
           $kin_phone_1, $kin_phone_2, $relationship;
    
    public function __construct($args = array())
    {
        $this->database_obj = Database::obj();
        $this->session_obj  = new Session();
        $this->kinship_obj  = new Kinship();
        
        // Initialize class properties
        $this->int_profile_id = isset($arr['document_id'])    ? $arr['document_id']:    '';
        $this->user_id        = isset($arr['user_id'])        ? $arr['user_id']:        '';
        $this->title          = isset($arr['title'])          ? $arr['title']:          '';
        $this->first_name     = isset($arr['first_name'])     ? $arr['first_name']:     '';
        $this->middle_name    = isset($arr['middle_name'])    ? $arr['middle_name']:    '';
        $this->surname        = isset($arr['surname'])        ? $arr['surname']:        '';
        $this->gender         = isset($arr['gender'])         ? $arr['gender']:         '';
        $this->address        = isset($arr['address'])        ? $arr['address']:        '';
        $this->email          = isset($arr['email'])          ? $arr['email']:          ''; 
        $this->phone_1        = isset($arr['phone_1'])        ? $arr['phone_1']:        '';
        $this->phone_2        = isset($arr['phone_2'])        ? $arr['phone_2']:        '';
        $this->date_of_birth  = isset($arr['date_of_birth'])  ? standardize_date($post['txt_dob'], '/'):  '0000-00-00 00:00:00';
        $this->marital_status = isset($arr['marital_status']) ? $arr['marital_status']: '';
        $this->religion       = isset($arr['religion'])       ? $arr['religion']:       '';
        $this->qualification  = isset($arr['qualification'])  ? $arr['qualification']:  '';
        $this->country        = isset($arr['country'])        ? $arr['country']:        '';
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
        $sql   .= (isset($args['and']))    ? " AND {$args['and']} "        : '';
        $sql   .= (isset($args['like']))   ? " LIKE {$args['like']} "      : '';
        $sql   .= (isset($args['group']))  ? " GROUP BY {$args['group']} " : '';
        $sql   .= (isset($args['order']))  ? " ORDER BY {$args['order']} " : '';
        $sql   .= (isset($args['limit']))  ? " LIMIT {$args['limit']} "    : '';
        
        // Format
        $format = (isset($args['format'])) ? $args['format'] : 'Object';
        
        // Return
        return $this->database_obj->execute_query($sql,$format);
    }
    
    public static function who_am_i($user_id)
    {
        // Init.
        $names = '';
        
        if('' != $user_id)
        {
            // SQL
            $sql = "SELECT CONCAT(o.option_name,' ',i.surname,', ',i.first_name) as name
                    FROM ".self::$table_name." i
                    INNER JOIN options o
                    ON o.option_id=i.title
                    WHERE i.user_id='$user_id'
                    LIMIT 1";
            
            // Execute
            $exec = Database::obj()->execute_query($sql,'Array');
            
            // Display the Logged in User
            if (!empty($exec)) $names = $exec[0]['name'];
        }
        
        echo $names;
    }
    
    public function dropdown_list($option_name)
    {
        if('' != $option_name)
        {
            // SQL
            $sql = "SELECT CONCAT(o.option_name,' ',i.surname,', ',i.first_name) as name, i.int_profile_id as id
                    FROM ".sellf::table_name." i
                    INNER JOIN options o
                    ON o.option_id=i.title
                    WHERE i.user_id
                    IN (SELECT id FROM users WHERE user_type = (SELECT option_id FROM options WHERE option_name='{$option_name}' AND group_name='user'))
                    ORDER BY i.surname";
        
            // Execute
            return $this->database_obj->execute_query($sql,'Array');
        }
    }
    
    public function insert_profile()
    {
        // Start transaction
        $this->database_obj->begin_transaction();
        
        // Transaction status
        if ($status === true)
        {
            if ($this->kinship_obj instanceof Kinship)
            {
                // Insert the Kinship (Next of Kin)
                
            }
            else
            {
                $status = false;
            }
               
        }else{
             // Roll back transaction
            $this->database_obj->roll_back();
        }
        return $status;
    }
    
    public function update_profile()
    {
        // Start transaction
        $this->database_obj->begin_transaction();
        
        // Transaction status
        if ($status === true)
        {
            /*if ($this->kinship_obj instanceof Kinship)
            {
                // Insert the Kinship (Next of Kin)
                //$status = $this->kinship_obj->update_kinship($post, $this->session_obj->user_id);
            }
            else
            {
                $status = false;
            }*/
               
        }else{
             // Roll back transaction
            $this->database_obj->roll_back();
        }
        
        return $status;
    }
    
    /**
     * Int_Profile::fetch_profile_by_user_id()
     * 
     * Fetches an internal Profile based on a specified User ID 
     * 
     * @param mixed $user_id
     * @return Returns an internal User Profile
     */
    public function fetch_profile_by_user_id($user_id)
    {
        // SQL
        $sql = "SELECT i.title, i.first_name, i.middle_name, i.surname, i.gender, i.address, i.email, i.phone_1, i.phone_2,
                       i.date_of_birth, i.marital_status, i.religion, i.qualification, i.country, k.first_name AS kin_first_name,
                       k.middle_name AS kin_middle_name, k.surname AS kin_surname, k.gender AS kin_gender, k.address AS 
                       kin_address, k.email AS kin_email, k.phone_1 AS kin_phone_1, k.phone_2 AS kin_phone_2, k.relationship AS relationship
                FROM ".self::$table_name." i
                INNER JOIN kinships k
                ON i.int_profile_id=k.int_profile_id
                WHERE i.user_id={$user_id}
                LIMIT 1
                ";
        
        // Result
        return self::initialize_result_vars($this->database_obj->execute_query($sql,'A'));
    }
    
    /**
     * Int_Profile::initialize_result_vars()
     * 
     * Initializes class attributes with query result
     * 
     * @param mixed $args
     * @return void
     */
    private function initialize_result_vars($args = array())
    {
        // Retreive the first index $args[0] of the multi-dymensional array returned by PDO fetchAll(PDO::FETCH_ASSOC)   
        foreach($args as $index)
        {
            // Builds all indexes into their corresponding Class properties
            foreach($index as $key => $val)
            {
                // Will only accept keys that have been explicitly defined as Class property
                if(property_exists($this, $key))
                {
                    // Will only assign values to Class attributes if the specified key is set
                    if(isset($key))
                    {
                        $this->{$key} = $val;
                    }
                }
            }
        }
        // Returns initialied Class attributes with $this for possible method chaining
        return $this;
    }
    

} ?>

