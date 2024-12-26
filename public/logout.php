<?php
require ".../public/controller/sess.php";

$_SESSION['login'] = 'false';
session_destroy(); 


header('Location: .../public/view/index.php');
?>