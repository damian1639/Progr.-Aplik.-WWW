<?php
//wylogowanie 
session_start();

if (!empty($_SESSION['admin_logged_in'])) {
    session_unset(); 
    session_destroy(); 
}

header('Location: ./admin.php');
exit;
?>