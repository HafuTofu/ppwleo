<?php
require "./sess.php";

$_SESSION['login'] = 'false';
session_destroy(); 


header('Location: ./login.php');
?>