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