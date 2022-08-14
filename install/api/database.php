<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        exit(http_response_code(404));
    include ('../../api/header.php');
    $diff = checkForEmptyKeysForm($post, ["host", "mysql-username"]);

    $db = new Database($post['host'], $post['mysql-password'] ?: '', $post['mysql-username'], '', is_numeric($post['port']) ? $post['port'] : 3306);
    $success = $db->tryConnection();
    setResponse(
        $success ? 200 : 406,
        $success ? 'OK' : "Les données de connexion à la base de données sont invalides",
        $success ? [] : ["host", "mysql-username", "mysql-password"],
        $success
    );