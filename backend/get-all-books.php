<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

getAllBooks();

/**
 * @param string book_name [поле GET Зарпоса]
 * @echo array
 */

function getAllBooks()
{
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        echo
                ApiHelper::api(
                    ApiHelper::HTTP_STATUS_OK,
                    Database::getBookByName()
            );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows GET method only"
            );
    }
}
