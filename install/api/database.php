<?php
    include ('../../api/header.php');
    checkForEmptyKeysForm($post, ["host", "username"]);
    $emptyKeys = checkForKeys($post, ["host", "username"]);

    if (count($emptyKeys) > 0) {
        setResponse(
            400,
            "Tout les champs obligatoires doivent être remplis",
            $emptyKeys,
            false
        );
    }

    $db = new Database($post['host'], $post['password'], $post['username'], '', is_numeric($post['port']) ?: 3306);
    $success = $db->tryConnection();
    setResponse(
        $success ? 200 : 401,
        $success ? 'OK' : "Les données de connexion à la base de données sont invalides",
        $diff,
        $success
    );