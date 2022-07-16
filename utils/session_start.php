<?php 
session_start();
if (!isset($_SESSION['computing_id'])) {
    header("Location: Login.php");
}
$id = $_SESSION['computing_id'];

function is_professor() {
    return $_SESSION['type'] === 'professor';
}
?>