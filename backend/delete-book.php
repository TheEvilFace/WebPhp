<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

deleteBook();

function deleteBook()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $post = $_POST;

        if (!isset($post['book_id'])) {
            echo ApiHelper::api(
                ApiHelper::HTTP_STATUS_BAD_REQUEST,
                "Field \"book_id\" is required"
            );
            return;
        }

        $book_id = $post['book_id'];

        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_OK,
            Database::deleteBook( $book_id)
        );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows POST method only"
        );
    }
}