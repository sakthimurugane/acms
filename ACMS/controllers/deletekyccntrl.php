<?php

require_once '../app_config/config.php';
require_once '../app_config/mysqlcon.php';

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if(get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}

//Start session
session_start();

//Validation error flag
$errflag = false;
$errmsg="";
//Default values


//print_r($_REQUEST);
$profileid='';
$fileid='';
if(isset($_REQUEST['pfid'])){
    $profileid=clean($_REQUEST['pfid']);
}
if(isset($_REQUEST['file_id'])){
    $fileid=clean($_REQUEST['file_id']);
}


 //2. delete document details
 
$delprofileqry = "UPDATE PROFILE_UPLOADS set IS_DELETED=1 where PROFILE_ID=".$profileid." and profile_uploads_id=".$fileid;
    
    if ($link->query($delprofileqry) === FALSE) {
   
        echo "Error: " . $newprofilequery . "<br>" . $link->error;
        $errflag=true;
        $errmsg="Error while deleting documents details. ".$link->error;
    }
    
    

    echo $errmsg;
    
    $link->close();
    
        if($errflag){
            $_SESSION['ERRMSG_DELKYC']=$errmsg;
        }else{
            $_SESSION['SCSMSG_DELKYC']="File ID: " .$fileid." has been removed from the system";
        }
    
        if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
        }else{
            header("location: ../dash/profile.php");
        }
?>