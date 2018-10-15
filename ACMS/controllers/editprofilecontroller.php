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

$profileid=1;
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
$modified_by='SYSTEM';

if(isset($_SESSION['SESS_MEMBER_ID'])){
    $modified_by=$_SESSION['SESS_MEMBER_ID'];
}
if(isset($_POST['submitaddprofile'])){
    $profileid=clean($_POST['profileid']);
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
    $profile_nation=$_POST['pfnationality'];
    $profile_GSTIN=$_POST['pfgstin'];
    $profile_passport=$_POST['pfpassprt'];

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

echo "input request <br/>";
print_r($_POST);
echo"========<br/>";

echo "new list request <br/>";
print_r($servicelist);
echo"========<br/>";



 //2. Inser profile details
 
    $newprofilequery = "UPDATE profiles set profile_name='$profile_name', owner_name='$owner_name', father_name='$father_name', referrer_id=$referrer_id, email='$profile_email', mobile='$profile_mobile'".
                        ", contact_person='$contact_name', contact_person_mobile='$contact_mobile', nation='$profile_nation', modified_on=now(), modified_by='$modified_by' where profile_id=$profileid";
        
    
    if ($link->query($newprofilequery) === TRUE) {
        echo "New record created successfully. Last updated ID is: ".$profileid." <br/>";
    } else {
        echo "Error: " . $newprofilequery . "<br>" . $link->error;
        $errflag=true;
        $errmsg="Error while updating profile details. ".$link->error;
    }
    
    if(!$errflag){
        $newkycquery = "UPDATE profile_kyc set kyc_number='$profile_aadhar', modified_on=now(), modified_by='$modified_by' where kyc_id=1 and profile_id=$profileid";
        if ($link->query($newkycquery) === FALSE) {
            $errflag=true;
            $errmsg="Error while updating contact details. ".$link->error;
        }
    }
    if(!$errflag){
        $newkycquery = "UPDATE profile_kyc set kyc_number='$profile_pan', modified_on=now(), modified_by='$modified_by' where kyc_id=2 and profile_id=$profileid";
        if ($link->query($newkycquery) === FALSE) {
            $errflag=true;
            $errmsg="Error while updating contact details. ".$link->error;
        }
    }
    if(!$errflag){
        $newkycquery = "UPDATE profile_kyc set kyc_number='$profile_GSTIN', modified_on=now(), modified_by='$modified_by' where kyc_id=3 and profile_id=$profileid";
        if ($link->query($newkycquery) === FALSE) {
            $errflag=true;
            $errmsg="Error while updating contact details. ".$link->error;
        }
    }
    if(!$errflag){
        $newkycquery = "UPDATE profile_kyc set kyc_number='$profile_passport', modified_on=now(), modified_by='$modified_by' where kyc_id=4 and profile_id=$profileid";
        if ($link->query($newkycquery) === FALSE) {
            $errflag=true;
            $errmsg="Error while updating contact details. ".$link->error;
        }
    }
    
    
//3. insert contact details
    if(!$errflag){
    $newcontactquery = "UPDATE profile_contact set contact_details='$home_addr', modified_on=now(), modified_by='$modified_by' where contact_type='HOME' and profile_id=$profileid";    
    if($link->query($newcontactquery) === FALSE){
        $errflag=true;
        $errmsg="Error while updating Home contact details. ".$link->error;
        
        
    }
    }
    if(!$errflag){
        $newcontactquery = "UPDATE profile_contact set contact_details='$office_addr', modified_on=now(), modified_by='$modified_by' where contact_type='OFFICE' and profile_id=$profileid";
    if($link->query($newcontactquery) === FALSE){
        $errflag=true;
        $errmsg="Error while inserting office contact details. ".$link->error;
        
    }
    
    }
    //4. insert service details details
    
     $dbactservicelist=array();
     $dbdelservicelist=array();
     
     $dbactserviceqry="select sub_service_id from profile_services where is_deleted=0 and profile_id=$profileid";
     $dbservicers=mysqli_query($link,$dbactserviceqry);
     if($dbservicers) {
         if(mysqli_num_rows($dbservicers) > 0) {
             while($row = $dbservicers->fetch_assoc()){
                 array_push($dbactservicelist,$row['sub_service_id']);
             }
         }
     }
     
     $dbdelserviceqry="select sub_service_id from profile_services where is_deleted=1 and profile_id=$profileid";
     $dbdelservicers=mysqli_query($link,$dbdelserviceqry);
     if($dbdelservicers) {
         if(mysqli_num_rows($dbdelservicers) > 0) {
             while($row = $dbdelservicers->fetch_assoc()){
                 array_push($dbdelservicelist,$row['sub_service_id']);
             }
         }
     }
     for($x=0;$x<count($dbactservicelist);$x++){
         if(!in_array($dbactservicelist[$x], $servicelist)){
             echo "1 Chnaged to Inactive - Deleting $dbactservicelist[$x] <br/>";
             mysqli_query($link, "update profile_services set is_deleted=1, modified_on=now(), modified_by='$modified_by' where sub_service_id=".$dbactservicelist[$x]." and profile_id=".$profileid);
         }else{
             if (($key = array_search($dbactservicelist[$x], $servicelist)) !== false) {
                 unset($servicelist[$key]);
                 echo "2 Already in  active - Removed $dbactservicelist[$x] <br/>";
                 
             }
         }
     }
     //echo "5 dbactservicelist <br/>" ;
     //print_r($dbactservicelist);
     //echo "6 dbdelservicelist <br/>" ;
     
     //print_r($dbdelservicelist);
     //echo "7 servicelist <br/>" ;
     
     print_r($servicelist);
     for($x=0;$x<count($dbdelservicelist);$x++){
         if(in_array($dbdelservicelist[$x], $servicelist)){
             echo "3 Activated $dbactservicelist[$x] <br/>";
             
             mysqli_query($link, "update profile_services set is_deleted=0, modified_on=now(), modified_by='$modified_by' where sub_service_id=".$dbdelservicelist[$x]." and profile_id=".$profileid);
             if (($key = array_search($dbdelservicelist[$x], $servicelist)) !== false) {
                 unset($servicelist[$key]);
                 echo "4 Removed $dbdelservicelist[$x] <br/>";
                 
             }
         }
     }
     //echo "8 servicelist <br/>" ;
     
     //print_r($servicelist);
     
     foreach($servicelist as $newlist){
         $newservicequery = "INSERT INTO profile_services (profile_id, sub_service_id, created_on, created_by)".
             "values ($profileid,$newlist,now(),'$modified_by')";
         if($link->query($newservicequery) === FALSE){
             $errflag=true;
             $errmsg="Error while inserting service details. ".$link->error;
             
         }
     }

    echo $errmsg;
    
    $link->close();
    
    if($errflag){
        $_SESSION['ERRMSG_EDITPROFILE']=$errmsg;
    }else{
        $_SESSION['SCSMSG_EDITPROFILE']="Profile ID: " .$profileid." has been updated in the system";
    }
    
        if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
        }else{
            header("location: ../dash/addprofile.php");
        }
?>