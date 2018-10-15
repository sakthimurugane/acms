<?php
    require_once 'config.php';
/* Database config */
    $db_host		= $GLOBALS['DB_HOST'];
    $db_user		= $GLOBALS['DB_USER'];
    $db_pass		= $GLOBALS['DB_PASSWORD'];
    $db_database	= $GLOBALS['DB_NAME'];

/* End config */

$db = new PDO('mysql:host='.$db_host.';dbname='.$db_database, $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
