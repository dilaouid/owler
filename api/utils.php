<?php
    function setResponse($status = 200, $message = null, $data = [], $success = true) {
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