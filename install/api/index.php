<?php
    include ('../../api/header.php');
    checkForEmptyKeysForm($post, [
        "host",
        "username",
        "platform_name",
        "admin_firstname",
        "admin_lastname",
        "admin_email",
        "admin_password",
        "admin_confirm_password",
        "condition_1",
        "condition_2",
        "condition_3",
        "condition_4"]
    );

    /////////////////////////////////////////
    // Check for the first step of the install form - mysql credentials
    /////////////////////////////////////////

    $db = new Database($post['host'], $post['password'], $post['username'], '', is_numeric($post['port']) ?: 3306);
    $err = [];
    if ($db->tryConnection() == false)
        array_push($err, ["step-1-form" => true]);
    
    /////////////////////////////////////////
    // Check for the second step of the install form - platform informations
    /////////////////////////////////////////

    if (strlen($post["platform_name"]) < 3 || strlen($post["platform_name"]) > 20)
        array_push($err, ["step-2-form" => "platform_name"]);
    
    /////////////////////////////////////////
    // Check for the third step of the install form - admin informations
    /////////////////////////////////////////

    // Between 8 and 20 characters, including a lowercase, uppercase, digit and symbol
    $passwordPattern = '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$';
    if (preg_match($passwordPattern, $post['admin_password']))
        array_push($err, ["step-3-form" => "admin_password"]);
    else if ($post["confirm_admin_password"] !== $post["admin_password"])
        array_push($err, ["step-3-form" => "confirm_admin_password"]);

    // Temporary - no insert in base but just checking if the input are all valid (:
    $success = count($err) == 0;
    setResponse(
        $success ? 200 : 401,
        $success ? 'OK' : "Le formulaire saisit est incorrect.",
        $err,
        $success
    );
    
    