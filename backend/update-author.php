<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

updateAuthor();

function updateAuthor()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $post = $_POST;
        if (!isset($post['author_id'])) {
            echo ApiHelper::api(
                ApiHelper::HTTP_STATUS_BAD_REQUEST,
                "Field \"author_id\" is required"
            );
            return;
        }

        if (!isset($post['author_name'])){
            echo ApiHelper::api(
                    ApiHelper::HTTP_STATUS_BAD_REQUEST,
                    "Field \"author_name\" is required"
                );
            return;
        }


        $author_id = $post['author_id'];
        $author_name = $post['author_name'];

        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_OK,
            Database::updateAuthor($author_id, $author_name)
        );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows POST method only"
        );
    }
}
