<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/ppwleo/connect.php';
    
    if(!isset($_SESSION['login'])){
        $_SESSION['login'] = 'false';
    }else if($_SESSION['login'] === 'false'){
        header("Location: {$_SERVER['DOCUMENT_ROOT']}/public/view/login.php");
    }
?>