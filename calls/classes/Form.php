<?php
    class Form
    {
        /**
         * Form::textbox()
         * 
         * e.g.
         * 
         * $form->textbox('txt_name','',array('onclick'=>'alert("I am very happy!")'));
         * 
         * @param mixed $name
         * @param string $value
         * @param mixed $action_arr
         * @return void
         */
        function textbox($name,$value='',$action_arr=array())
        {
            // Init.
            $textbox = '';
            
            $textbox  = "<div class='text_box'>";
            $textbox .= "<input type='text' name='$name' id='$name' value='$value' class='text'";
            
            if (!empty($action_arr))
            {                    
                foreach($action_arr as $action_key => $action_value)
                {
                    $textbox .= " $action_key='$action_value' ";
                }
            }
            
            $textbox .= "/>";
            $textbox .= "</div>";
            
            echo $textbox;                        
        }
        
        /**
         * Form::select()
         * 
         * @param mixed $option_arr
         * @param mixed $name
         * @param string $value
         * @param string $default
         * @param mixed $action_arr
         * @return void
         */
        function select($option_arr,$name,$value='',$default='Select:',$action_arr=array())
        {
            // Init.
            $select = '';
            
            if (!empty($option_arr))
            {
                $select .= "<div class='select_box'>";
                $select .= "<select name='$name' id='$name' class='select'";
            
                if (!empty($action_arr))
                {
                    foreach($action_arr as $action_key => $action_value)
                    {
                        $select .= " $action_key='$action_value' ";
                    }
                }
                
                $select .= ">";
            
                // Setting the optional first value of the dropdown list
                if ($default != '') $select .= "<option value=''>$default</option>";
                
                foreach ($option_arr as $option)
                {
                    $selected = '';
                    if ($option['id'] == $value) $selected='SELECTED';
                    $select .= '<option value="'.$option['id'].'" '.$selected.'>'.$option['name'].'</option>';
                }
                
                $select .= "</select>";
                $select .= "</div>";
            }
            
            echo $select;
        }
    }
?>