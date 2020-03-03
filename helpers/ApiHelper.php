<?php


class ApiHelper
{
    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_BAD_REQUEST = 400;

    static function api($status_code, $result){
        return json_encode([
            'status' => $status_code,
            'message' => $result
        ]);
    }

}