<?php
    include("config/db.php");
    include("Class/Database.php");
    $db = new Database($host, $password, $username, $dbname);
    $db->init();
?>