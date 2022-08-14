<?php
    include ('../../api/header.php');
    $diff = checkForEmptyKeysForm($post, ["host", "mysql-username"]);

    $db = new Database($post['host'], $post['mysql-password'] ?: '', $post['mysql-username'], '', is_numeric($post['port']) ?: 3306);
    $success = $db->tryConnection();
    setResponse(
        $success ? 200 : 401,
        $success ? 'OK' : "Les données de connexion à la base de données sont invalides",
        $success ? [] : ["host", "mysql-username", "mysql-password"],
        $success
    );