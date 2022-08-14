<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        exit(http_response_code(404));
    include('../../api/header.php');
    checkForEmptyKeysForm($post, ["admin_firstname", "admin_lastname", "admin_email", "admin_password", "confirm_admin_password"]);
    $err = [];
    if (filter_var($post["admin_email"], FILTER_VALIDATE_EMAIL) == false)
        array_push($err, ["admin_email" => "L'adresse email saisit est invalide"]);

    // Between 8 and 20 characters, including a lowercase, uppercase, digit and symbol
    $passwordPattern = '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$';

    if (!preg_match($passwordPattern, $post['admin_password']))
        array_push($err, ["admin_password" => "Le mot de passe doit contenir au moins 8 caractères, une minuscule, une majuscule, un symbole et un chiffre"]);
    else if ($post["confirm_admin_password"] !== $post["admin_password"])
        array_push($err, ["confirm_admin_password" => "Les mots de passes saisits ne sont pas identiques"]);
    
    $success = count($err) == 0;
    setResponse(
        $success ? 200 : 406,
        $success ? 'OK' : "Le formulaire saisit est incorrect.",
        $err,
        $success
    );