<?php
    include ('../../api/header.php');

    checkForEmptyKeysForm($post, ["platform_name"]);
    $success = strlen($post["platform_name"]) >= 3 && strlen($post["platform_name"]) <= 20;
    setResponse(
        $success ? 200 : 401,
        $success ? 'OK' : "Le nom de votre plateforme de connexion doit faire entre 3 et 20 caractères.",
        $success ? [] : ["platform_name"],
        $success
    );
