<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

searchByBook();

/**
 * @param string book_name [поле GET Зарпоса]
 * @echo array
 */
function searchByBook()
{
    if ($_SERVER['REQUEST_METHOD'] == "GET") {

        $get = $_GET;
        if (!isset($get['book_name']))
            echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Field \"book_name\" is required"
            );

        $book_name = $get['book_name'];

        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_OK,
            Database::getBookByName($book_name)
        );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows GET method only"
        );
    }
}
