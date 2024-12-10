<?php
    include '../connect.php';
    
    if(!isset($_SESSION['login'])){
        $_SESSION['login'] = 'false';
    }else if($_SESSION['login'] === 'false'){
        header("Location: ./login.php");
    }
?>