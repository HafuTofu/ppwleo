<?php
require "../public/sess.php";

$_SESSION['login'] = 'false';
session_destroy(); 


header('Location: ../public/index.php');
?>