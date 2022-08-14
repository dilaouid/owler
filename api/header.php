<?php
    $dirname = dirname(__FILE__, 2);
    include ('utils.php');
    include($dirname . './Class/Database.php');
    if (file_exists($dirname . '/config/db.php'))
        include ($dirname . '/config/header_pdo.php');
    
    // The API routes are all sending a json
    header('Content-Type: application/json; charset=utf-8');

    $get = protectAndTrim($_GET);
    $post = protectAndTrim($_POST);
    
?>