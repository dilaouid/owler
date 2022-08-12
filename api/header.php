<?php
    include ('utils.php');
    include('../Class/Database.php');

    // The API routes are all sending a json
    header('Content-Type: application/json; charset=utf-8');

    $get = protectAndTrim($_GET);
    $post = protectAndTrim($_POST);
    
?>