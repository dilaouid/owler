<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        exit(http_response_code(404));
    include('form.php');
    include ('../../api/header.php');

    checkForEmptyKeysForm($post, get_required_input($form[1]["data"]));
    $success = strlen($post["platform_name"]) >= 3 && strlen($post["platform_name"]) <= 20;
    setResponse(
        $success ? 200 : 406,
        $success ? 'OK' : "Le nom de votre plateforme de connexion doit faire entre 3 et 20 caractères.",
        $success ? [] : ["platform_name"],
        $success
    );
