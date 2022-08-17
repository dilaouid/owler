<?php
    function setResponse($status = 200, $message = null, $data = [], $success = true) {
        http_response_code($status);
        echo json_encode( (object) array(
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "success" => $success
        ));
        exit();
    };

    function checkForKeys($arr, $keys) {
        $diff = [];
        if (count($arr) == 0) return ($keys);
        for ($i=0; $i < count($keys); $i++) { 
            $key = $keys[$i];
            if (array_key_exists($key, $arr) == false || strlen($arr[$key]) == 0)
                array_push($diff, $key);
        }
        return ($diff);
    }

    function protectAndTrim($arr) {
        $protect = array_map('htmlentities', $arr);
        return (array_map('trim', $protect));
    }

    function checkForEmptyKeysForm($post, $keys) {
        $emptyKeys = checkForKeys($post, $keys);
        if (count($emptyKeys) > 0) {
            setResponse(
                406,
                "Tout les champs obligatoires doivent être remplis",
                $emptyKeys,
                false
            );
        }
    }

    function parse_login($start, $firstname, $lastname) {
        $prefix = substr($firstname, 0, $start + 1);
        return strtolower(substr($prefix . $lastname, 0, 10));
    }

    function fetch($mysql, $login) {
        $query = $mysql->query("SELECT COUNT(login) AS 'count' FROM users WHERE login = '". $login ."'");
        $query->execute();
        $rows = $query->fetch(PDO::FETCH_ASSOC);
        return $rows['count'];
    }

    function define_login($mysql, $firstname, $lastname) {
        $idx = 0;
        $login = parse_login($idx, $firstname, $lastname);
        $suffix = 1;
        $prefixIdx = 1;
        while (fetch($mysql, $login) > 0) {
            if ($idx >= strlen($firstname)) {
                if (strlen($firstname) >= $prefixIdx - 1) {
                    $prefixIdx = 1;
                    $suffix = 1;
                }
                $login = parse_login($prefixIdx - 1, $firstname, $lastname) . $suffix;
                $prefixIdx++;
            } else
                $login = parse_login($idx++, $firstname, $lastname);
        }
        return ($login);
    }