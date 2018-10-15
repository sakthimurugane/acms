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
$credid='';
$creddesc='';
$credlogin='';
$credpass='';
$credmfa='';
$modified_by='SYSTEM';

if(isset($_SESSION['SESS_MEMBER_ID'])){
    $modified_by=$_SESSION['SESS_MEMBER_ID'];
}

if(isset($_POST['updatecred'])){
    
    $credid=$_POST['credid'];
    $creddesc=$_POST['ecredDesc'];
    $credlogin=$_POST['ecredLogin'];
    $credpass=$_POST['ecredPass'];
    $credmfa=$_POST['ecredMFA'];
    
}



 //2. add credenntials details
 
    $updcredeqry = "UPDATE PROFILE_CREDENTIALS SET cred_desc='$creddesc', cred_login='$credlogin', cred_password='$credpass', cred_mfa_details='$credmfa' ".
                    ", modified_on=now(), modified_by='$modified_by' where profile_cred_id=$credid";
    
    if ($link->query($updcredeqry) === FALSE) {
   
        echo "Error: " . $updcredeqry . "<br>" . $link->error;
        $errflag=true;
        $errmsg="Error while inserting credentials. ".$link->error;
    }
    
    

    echo $errmsg;
    
    $link->close();
    
        if($errflag){
            $_SESSION['ERRMSG_EDITCRED']=$errmsg.$updcredeqry   ;
        }else{
            $_SESSION['SCSMSG_EDITCRED']="Credential has been updated into the system";
        }
    
        if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
        }else{
            header("location: ../dash/profile.php");
        }
?>