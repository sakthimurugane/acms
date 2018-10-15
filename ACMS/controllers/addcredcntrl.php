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
$credtype='';
$creddesc='';
$credlogin='';
$credpass='';
$credmfa='';
$created_by='SYSTEM';

if(isset($_SESSION['SESS_MEMBER_ID'])){
    $created_by=$_SESSION['SESS_MEMBER_ID'];
}

if(isset($_POST['submitcred'])){
    
    $profileid=$_POST['profileid'];
    $credtype=$_POST['credType'];
    $creddesc=$_POST['credDesc'];
    $credlogin=$_POST['credLogin'];
    $credpass=$_POST['credPass'];
    $credmfa=$_POST['credMFA'];
    
}



 //2. add credenntials details
 
    $newcredeqry = "INSERT INTO PROFILE_CREDENTIALS (profile_id,cred_login,cred_password,cred_mfa_details,cred_type,cred_desc,created_on,created_by)".
                    "values($profileid,'$credlogin','$credpass','$credmfa','$credtype','$creddesc',now(),'$created_by')";
    
    if ($link->query($newcredeqry) === FALSE) {
   
        echo "Error: " . $newcredeqry . "<br>" . $link->error;
        $errflag=true;
        $errmsg="Error while inserting credentials. ".$link->error;
    }
    
    

    echo $errmsg;
    
    $link->close();
    
        if($errflag){
            $_SESSION['ERRMSG_ADDCRED']=$errmsg;
        }else{
            $_SESSION['SCSMSG_ADDCRED']="Credential has been added into the system";
        }
    
        if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
        }else{
            header("location: ../dash/profile.php");
        }
?>