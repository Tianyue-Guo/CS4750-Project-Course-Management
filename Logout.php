<?php
    // session_start();
    // Because you are checking if(isset($_SESSION['loggedin'])), use the below:
    session_start();
    session_destroy();
    header('Location: Login.php');
    //echo 'bye';
    exit;
?>