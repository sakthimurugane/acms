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
$profile_id='';
if(isset($_REQUEST['pfid'])){
    $profile_id=clean($_REQUEST['pfid']);
}


 //2. Inser profile details
 
    $delprofileqry = "UPDATE PROFILES set IS_DELETED=1 where PROFILE_ID=".$profile_id;
    
    if ($link->query($delprofileqry) === FALSE) {
   
        echo "Error: " . $newprofilequery . "<br>" . $link->error;
        $errflag=true;
        $errmsg="Error while deleting profile details. ".$link->error;
    }
    
    

    echo $errmsg;
    
    $link->close();
    
        if($errflag){
            $_SESSION['ERRMSG_DELPROFILE']=$errmsg;
        }else{
            $_SESSION['SCSMSG_DELPROFILE']="Profile ID: " .$profile_id." has been removed from the system";
        }
    
        if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
        }else{
            header("location: ../dash/profile.php");
        }
?>