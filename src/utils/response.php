<?php

function setResponse($status = 200, $message = null, $data = []) {
    return json_encode( (object) array(
        "status" => $status,
        "message" => $message,
        "data" => $data
    ));
};
