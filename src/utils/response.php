<?php

function setResponse($message = null, $data = []) {
    return json_encode( (object) array(
        "message" => $message,
        "data" => $data
    ));
};
