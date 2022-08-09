<?php
    if (is_dir('install')) {
        header('Location: install/index.php');
        exit();
    }
    include("config/header_pdo.php");
?>

Inside ./index.php