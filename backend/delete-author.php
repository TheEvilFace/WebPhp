<?php
require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

deleteAuthor();

function deleteAuthor()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $post = $_POST;

        if (!isset($post['author_id ']))  echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Field \"author_id\" is required"
        );

        $author_id = $post['author_id'];

        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_OK,
            Database::deleteAuthor( $author_id)
        );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows POST method only"
        );
    }
}