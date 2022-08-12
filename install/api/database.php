<?php
    include ('../../api/header.php');
    checkForEmptyKeysForm($post, ["host", "username"]);

    $db = new Database($post['host'], $post['password'], $post['username'], '', is_numeric($post['port']) ?: 3306);
    $success = $db->tryConnection();
    setResponse(
        $success ? 200 : 401,
        $success ? 'OK' : "Les données de connexion à la base de données sont invalides",
        $diff,
        $success
    );