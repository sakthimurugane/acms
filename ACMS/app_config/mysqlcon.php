<?php
    require_once 'config.php';

    /* Database config */
    $db_host        = $GLOBALS['DB_HOST'];
    $db_user        = $GLOBALS['DB_USER'];
    $db_pass        = $GLOBALS['DB_PASSWORD'];
    $db_database    = $GLOBALS['DB_NAME'];
    
    /* End config */

    
    //Connect to mysql server
    $link = mysqli_connect($db_host,$db_user,$db_pass,$db_database);
    if(!$link) {
        die('Failed to connect to server: ' . mysql_error());
    }

?>
