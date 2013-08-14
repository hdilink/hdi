<?php
    require_once( '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init.php' );
    require_once( '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'functions.php' );
    require_once( PLUGINS.DIRECTORY_SEPARATOR.'ThumbLib.inc.php' );
    
    // Init.
    $json = $doc_arr = array();
    
    if(!empty($_FILES['fil_patient_pix']['name']))
    {
        
        if ($_FILES['fil_patient_pix']['error'] === UPLOAD_ERR_OK)
        {
            $upload_path              = UPLOADS.DIRECTORY_SEPARATOR."pictures".DIRECTORY_SEPARATOR."thumbs".DIRECTORY_SEPARATOR;
            $extension                = pathinfo(basename($_FILES['fil_patient_pix']['name']), PATHINFO_EXTENSION);
            $new_name                 = str_replace('/', '-', $_POST['txt_pid_alias']);
            $doc_arr['filename']      = $new_name.'.'.$extension;
            $doc_arr['type']          = $_FILES['fil_patient_pix']['type'];
            $doc_arr['size']          = $_FILES['fil_patient_pix']['size'];
            $doc_arr['thumb_path']    = THUMB_PATH;
            $doc_arr['date_created']  = get_current_date();
            $doc_arr['date_modified'] = get_current_date();
            
            $document = new Document($doc_arr);
            if($document instanceof Documents)
            {
                try
                {
                    $thumb = PhpThumbFactory::create($_FILES['fil_patient_pix']['tmp_name']);
                }
                catch (Exception $e)
                {
                    //handle errors
                    $json['error'] = $e->getMessage();
                }
                
                $thumb->adaptiveResize(100, 100);
                $thumb->save($upload_path.$doc['filename']);
                
                // Save the document and return the last inserted id
                $last_insert_id = $document->save_document($doc);
                
                if ('' != $last_insert_id)
                {
                    $json['pix'] = $doc['thumb_path'].$doc['filename'];
                    $json['id']  = $last_insert_id;
                }
            }
        
        } else {
            $json['error'] = $document->check_upload_errors($_FILES['fil_patient_pix']['error']);
        }
    } else {
        $json['error'] = "ERROR: Nofile was selected.";
    }
    echo json_encode($json);    
?>