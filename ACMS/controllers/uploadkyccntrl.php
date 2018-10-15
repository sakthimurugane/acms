
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
$errors= array();
$errflag = false;
$errmsg="";
//Default values


//print_r($_REQUEST);
$target_dir='../dash/uploads/';
$profileid='';
$filename='';
$filetype='';
$filedesc='';
$created_by='SYSTEM';
$filepath='uploads/';

if(isset($_POST['submitdoc'])){
    
    if(isset($_SESSION['SESS_MEMBER_ID'])){
        $created_by=$_SESSION['SESS_MEMBER_ID'];
    }
    
    
    $profileid=clean($_POST['profileid']);
    $filename=basename($_FILES["fileToUpload"]["name"]);
    $filetype=clean($_POST['fileType']);
    $filedesc=clean($_POST['fileDesc']);
    $file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $file_size = $_FILES['fileToUpload']['size'];
    
    
    $expensions= array("jpeg","jpg","png","doc", "docx","pdf", "xls", ".xlsx", "txt" ,"gif");
    
    if(in_array($file_ext,$expensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }
    
    if($file_size > 3145728) {
        $errors[]='File size must be excately 3 MB';
    }
    
    if(empty($errors)==true) {
        move_uploaded_file($file_tmp,$target_dir.$profileid.'_'.$filename);
        $filepath .=$profileid.'_'.$filename;
    }else{
        print_r($errors);
    }
    
    //2. Update in the db
       
    $uplqry="INSERT INTO PROFILE_UPLOADS (profile_id,file_name,file_desc,file_type,file_path,created_on,created_by) values".
        "($profileid,'$filename','$filedesc','$filetype','$filepath',now(),'$created_by')";
    if ($link->query($uplqry) === FALSE) {
        $errflag=true;
        $errmsg="Error while inserting document details. ".$link->error;
    }
}

    
    $link->close();
    
        if($errflag){
            $_SESSION['ERRMSG_UPLOADKYC']=$errmsg;
        }else{
            $_SESSION['SCSMSG_UPLOADKYC']="Documents upload success";
        }
    
        
        if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
        }else{
            header("location: ../dash/profile.php");
        }
?>