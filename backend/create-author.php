<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

createAuthor();

function createAuthor()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $post = $_POST;
        $files = $_FILES;

        if (!isset($post['author_name']) || !isset($files['author_pic'])){
            echo ApiHelper::api(
                ApiHelper::HTTP_STATUS_BAD_REQUEST,
                "Fields \"author_name\", \"author_pic\" are required"
            );
            return;
        }

        $author_name = $post['author_name'];
        $author_pic = $files['author_pic'];

        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_OK,
            Database::createAuthor($author_name, $author_pic)
        );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows POST method only"
        );
    }
}