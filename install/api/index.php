<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        exit(http_response_code(404));
    include ('../../api/header.php');
    checkForEmptyKeysForm($post, [
        "host",
        "mysql-username",
        "platform_name",
        "admin_firstname",
        "admin_lastname",
        "admin_email",
        "admin_password",
        "confirm_admin_password",
        "condition_1",
        "condition_2",
        "condition_3",
        "condition_4"]
    );

    function mv($src, $dst) { 
        $dir = opendir($src);
        @mkdir($dst);
        while( $file = readdir($dir) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) 
                    custom_copy($src . '/' . $file, $dst . '/' . $file); 
                else
                    copy($src . '/' . $file, $dst . '/' . $file); 
            }
        }
        closedir($dir);
    }

    function changeDefineFile($data) {
        $replaceDefine = [
            $data['platform_name'],
            $data['platform_description'],
            $data['host'],
            $data['mysql-username'],
            $data['mysql-password'],
            is_numeric($data['port']) ? $data['port'] : 3306
        ];
        $searchDefine = [
            "{PLATFORM_NAME}",
            "{PLATFORM_DESCRIPTION}",
            "{DB_HOST}",
            "{DB_USER}",
            "{DB_PASSWORD}",
            "{DB_PORT}",
        ];
        $file = file_get_contents("../../config/define.php");
        $file = str_replace($searchDefine, $replaceDefine, $file);
        $fd = fopen("../../config/define.php", 'w');
        fwrite($fd, $file);  
        fclose($fd);
    }

    function deleteDirectory($dir) {
        if (!file_exists($dir))
            return true;
        if (!is_dir($dir))
            return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
                return false;
        }
        return rmdir($dir);
    }

    /////////////////////////////////////////
    // Check for the first step of the install form - mysql credentials
    /////////////////////////////////////////

    $db = new Database($post['host'], $post['mysql-password'] ?: '', $post['mysql-username'], '', is_numeric($post['port']) ? $post['port'] : 3306);
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
    if (!preg_match($passwordPattern, $post['admin_password']))
        array_push($err, ["step-3-form" => "admin_password"]);
    else if ($post["confirm_admin_password"] !== $post["admin_password"])
        array_push($err, ["step-3-form" => "confirm_admin_password"]);

    // Temporary - no insert in base but just checking if the input are all valid (:
    $success = count($err) == 0;
    if ($success) {
        $db->mysql->query('CREATE DATABASE owler CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        $db = new Database($post['host'], $post['mysql-password'] ?: '', $post['mysql-username'], '', is_numeric($post['port']) ? $post['port'] : 3306);
        $db->dbname = 'owler';
        if ($db->tryConnection() == false) {
            array_push($err, ["step-1-form" => true]);
            $success = false;
        } else {
            $db->mysql->query(file_get_contents('../owler.sql'));
            /* if (PRODUCTION === false) {
                mv('.', '../api_tmp');
            } else {
                unlink('../owler.sql');
            } */
            changeDefineFile($post);
            $id = uniqid();
            $login = define_login($db->mysql, $post['admin_firstname'], $post['admin_lastname']);
            $password = password_hash($post['admin_password'], PASSWORD_DEFAULT);
            $values = set_values_column_to_string([$id, $post['admin_email'], $login, $post['admin_firstname'], $post['admin_lastname'], $password, 'admin']);
            $query = $db->mysql->query('INSERT INTO users (id, email, login, firstname, lastname, password, role) VALUES ('.$values.')');
            $query->execute();
        }
    }
    setResponse(
        $success ? 201 : 406,
        $success ? $login : "Le formulaire saisit est incorrect.",
        $err,
        $success
    );