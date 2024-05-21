<?php
    define('DB_SERVER', 'localhost');
    define('DB_PORT', '3307'); 
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'newpassword'); 
    define('DB_NAME', 'php_task');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>
