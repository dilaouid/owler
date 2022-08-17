<?php
    // If the "install" directory exists, it means that the software
    // is not installed, so let's redirect the user to the installation
    if (is_dir('install/api'))
        exit(header('Location: install/'));
    
    include("config/header_pdo.php");
?>

Inside ./index.php