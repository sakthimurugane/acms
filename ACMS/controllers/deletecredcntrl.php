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
$credid='';
if(isset($_REQUEST['pfid']) && isset($_REQUEST['credid'])){
    $profileid=clean($_REQUEST['pfid']);
    $credid=clean($_REQUEST['credid']);
}
else{
    if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
    }else{
        header("location: ../dash/profile.php");
    }
}



 //2. delete document details
 
$delcredqry = "UPDATE PROFILE_CREDENTIALS set IS_DELETED=1 where PROFILE_ID=".$profileid." and profile_cred_id=".$credid;
    
if ($link->query($delcredqry) === FALSE) {
   
    echo "Error: " . $delcredqry . "<br>" . $link->error;
        $errflag=true;
        $errmsg="Error while deleting credentials. ".$link->error;
    }
    
    

    echo $errmsg;
    
    $link->close();
    
        if($errflag){
            $_SESSION['ERRMSG_DELCRED']=$errmsg;
        }else{
            $_SESSION['SCSMSG_DELCRED']="Credentials has been removed from the system";
        }
    
        if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
        }else{
            header("location: ../dash/profile.php");
        }
?>