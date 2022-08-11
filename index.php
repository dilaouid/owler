<?php
    // If the "install" directory exists, it means that the software
    // is not installed, so let's redirect the user to the installation
    if (is_dir('install'))
        exit(header('Location: install/index.php'));
    
    include("config/header_pdo.php");
?>

Inside ./index.php