<?php
require_once '../app_config/config.php';
require_once '../app_config/mysqlcon.php';

//Start session
session_start();

//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;


//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if(get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}

//Sanitize the POST values
$login = clean($_POST['inusn']);
$password = clean($_POST['inpsw']);

//print_r($_SERVER);

//Input Validations
if($login == '') {
    $errmsg_arr[] = 'Username missing';
    $errflag = true;
}
if($password == '') {
    $errmsg_arr[] = 'Password missing';
    $errflag = true;
}

//If there are input validations, redirect back to the login form
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    header("location: ../login.php");
    exit();
}
//Setting Login activity parameters
$sorigin=$_SERVER['HTTP_ORIGIN'];
$raddr=$_SERVER['REMOTE_ADDR'];
$rport=$_SERVER['REMOTE_PORT'];
$rusag=$_SERVER['HTTP_USER_AGENT'];
$rmethod=$_SERVER['REQUEST_METHOD'];
$rtime=$_SERVER['REQUEST_TIME'];

$ins_loginactivity = "INSERT INTO LOGIN_ACTIVITY (HTTP_ORIGIN,REMOTE_ADDR,REMOTE_PORT,HTTP_UA,METHOD,REQUEST_TIME,CREATED_ON,CREATED_BY) VALUES ('$sorigin','$raddr','$rport','$rusag','$rmethod','$rtime',now(),'SYSTEM')";
if (mysqli_query($link, $ins_loginactivity)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $ins_loginactivity . "" . mysqli_error($link);
}

//Create query
$qry="SELECT * FROM person WHERE username='$login' AND password='$password'";
$result=mysqli_query($link,$qry);

//Check whether the query was successful or not
if($result) {
    if(mysqli_num_rows($result) > 0) {
        $member = mysqli_fetch_assoc($result);
        
        if($member['is_locked']==1){
            //Account is locked
            $errmsg_arr[] = 'Account is locked';
            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
            header("location: ../login.php");
            exit();
        }
        else{
        //Login Successful
        session_regenerate_id();
        $_SESSION['SESS_MEMBER_ID'] = $member['person_id'];
        $_SESSION['SESS_FIRST_NAME'] = $member['person_name'];
        $_SESSION['SESS_MEMBER_ROLE'] = $member['person_role'];
        session_write_close();
        $link->close();
        header("location: ../dash/index.php");
        exit();
        }
    }else {
        //Login failed
        $errmsg_arr[] = 'Invalid username or password';
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
        $link->close();
        header("location: ../login.php");
        exit();
    }
}else {
    $errmsg_arr[] = 'Unable to process your request. Please contact System Administrator';
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    die("Query failed");
}
?>
