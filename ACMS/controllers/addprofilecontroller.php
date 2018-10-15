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

$profile_id=1;
//Default values
$profile_name="";
$owner_name="";
$father_name="";
$referrer_id=0;
$home_addr="";
$office_addr="";
$profile_email="";
$profile_mobile="";
$profile_nation="";
$contact_name="";
$contact_mobile="";

//KYC details
$profile_aadhar="";
$profile_pan="";
$profile_GSTIN="";
$profile_passport="";

//service_details
$servicelist=array();

$created_by='SYSTEM';

print_r($_REQUEST);

if(isset($_SESSION['SESS_MEMBER_ID'])){
    $created_by=$_SESSION['SESS_MEMBER_ID'];
}
if(isset($_POST['submitaddprofile'])){
    $profile_name=clean($_POST['pfname']);
    $owner_name=clean($_POST['owname']);
    $father_name=clean($_POST['fathname']);
    $referrer_id=clean($_POST['selectreferer']);
    $home_addr=clean($_POST['homeaddr']);
    $office_addr=clean($_POST['officeaddr']);
    $profile_email=$_POST['pfemail'];
    $profile_mobile=$_POST['pfmobile'];
    $contact_name=$_POST['pfcname'];
    $contact_mobile=$_POST['pfcmobile'];
    $profile_aadhar=$_POST['pfaadhar'];
    $profile_pan=$_POST['pfpan'];
    $profile_GSTIN=$_POST['pfgstin'];
    $profile_passport=$_POST['pfpassprt'];
    $profile_nation=$_POST['pfnationality'];
    
    if(isset($_POST['srvcount'])){
        $tmpservcount=$_POST['srvcount'];
        for($x=1;$x<=$tmpservcount;$x++){
            if(isset($_POST['service'.$x])){
                $tmplist=array();
                $tmplist=$_POST['service'.$x];
                foreach($tmplist as $als)
                    array_push($servicelist,$als);
            }
        }
    }
    
    
    
}

 //2. Inser profile details
 
    $newprofilequery = "INSERT INTO profiles (profile_name, owner_name, father_name, referrer_id, email, mobile, contact_person, contact_person_mobile, nation, created_on, created_by)".
        "values ('$profile_name','$owner_name','$father_name',$referrer_id,'$profile_email','$profile_mobile','$contact_name','$contact_mobile','$profile_nation',now(),'$created_by')";
    
    if ($link->query($newprofilequery) === TRUE) {
        $profile_id = $link->insert_id;
        echo "New record created successfully. Last inserted ID is: " . $profile_id;
    } else {
        echo "Error: " . $newprofilequery . "<br>" . $link->error;
        $errflag=true;
        $errmsg="Error while inserting profile details. ".$link->error;
    }
    
    if(!$errflag){
        $newkycquery = "INSERT INTO profile_kyc (profile_id, kyc_id, kyc_number, created_on, created_by) ".
            "values ($profile_id,1,'$profile_aadhar',now(),'$created_by')";
        if ($link->query($newkycquery) === FALSE) {
            $errflag=true;
            $errmsg="Error while inserting contact details. ".$link->error;
        }
    }
    if(!$errflag){
        $newkycquery = "INSERT INTO profile_kyc (profile_id, kyc_id, kyc_number, created_on, created_by) ".
            "values ($profile_id,2,'$profile_pan',now(),'$created_by')";
        if ($link->query($newkycquery) === FALSE) {
            $errflag=true;
            $errmsg="Error while inserting contact details. ".$link->error;
        }
    }
    if(!$errflag){
        $newkycquery = "INSERT INTO profile_kyc (profile_id, kyc_id, kyc_number, created_on, created_by) ".
            "values ($profile_id,3,'$profile_GSTIN',now(),'$created_by')";
        if ($link->query($newkycquery) === FALSE) {
            $errflag=true;
            $errmsg="Error while inserting contact details. ".$link->error;
        }
    }
    if(!$errflag){
        $newkycquery = "INSERT INTO profile_kyc (profile_id, kyc_id, kyc_number, created_on, created_by) ".
            "values ($profile_id,4,'$profile_passport',now(),'$created_by')";
        if ($link->query($newkycquery) === FALSE) {
            $errflag=true;
            $errmsg="Error while inserting contact details. ".$link->error;
        }
    }
    
    
//3. insert contact details
    if(!$errflag){
    $newcontactquery = "INSERT INTO profile_contact (profile_id, contact_type, contact_details, created_on, created_by)".
        "values ($profile_id,'HOME','$home_addr',now(),$created_by);";
    
    if($link->query($newcontactquery) === FALSE){
        $errflag=true;
        $errmsg="Error while inserting Home contact details. ".$link->error;
        
        
    }
    }
    if(!$errflag){
    $newcontactquery = "INSERT INTO profile_contact (profile_id, contact_type, contact_details, created_on, created_by)".
        "values ($profile_id,'OFFICE','$office_addr',now(),'$created_by')";

    if($link->query($newcontactquery) === FALSE){
        $errflag=true;
        $errmsg="Error while inserting office contact details. ".$link->error;
        
    }
    
    }
    //4. insert service details details
    
    if(!$errflag){
        
            foreach($servicelist as $newlist){
                $newservicequery = "INSERT INTO profile_services (profile_id, sub_service_id, created_on, created_by)".
                    "values ($profile_id,$newlist,now(),'$created_by')";
                if($link->query($newservicequery) === FALSE){
                    $errflag=true;
                    $errmsg="Error while inserting service details. ".$link->error;
                    
                }
            }
        
        }



    echo $errmsg;
    
    $link->close();
    
    if($errflag){
        $_SESSION['ERRMSG_ADDPROFILE']=$errmsg;
    }else{
        $_SESSION['SCSMSG_ADPRF']="New profile with ID: " .$profile_id." has been added into system";
    }
    
        if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
        }else{
            header("location: ../dash/addprofile.php");
        }
?>