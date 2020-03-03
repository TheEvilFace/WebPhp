<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

getAuthors();

function getAuthors()
{
    if ($_SERVER['REQUEST_METHOD'] == "GET") {

        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_OK,
            Database::getAllAuthors()
        );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows GET method only"
        );
    }
}
