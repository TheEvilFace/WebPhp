<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

addAuthorToBook();

/**
 * Метод API, который позволяет добавить автора к книге
 */
function addAuthorToBook(){
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $post = $_POST;

        if(!isset($post['book_id'])){
            echo ApiHelper::api(
                ApiHelper::HTTP_STATUS_BAD_REQUEST,
                "Field \"book_id\" is required"
            );
            return;
        }

        if(!isset($post['author_id'])){
            echo ApiHelper::api(
                ApiHelper::HTTP_STATUS_BAD_REQUEST,
                "Field \"author_id\" is required"
            );
            return;
        }

        $author_id = $post['author_id'];
        $book_id = $post['book_id'];

        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_OK,
            Database::addAuthorToBook($author_id, $book_id)
        );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows POST method only"
        );
    }
}