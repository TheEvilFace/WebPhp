<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";

createBook();

function createBook()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $post = $_POST;
        $files = $_FILES;
        if(!isset($post['book_name'])){
             echo ApiHelper::api(
                  ApiHelper::HTTP_STATUS_BAD_REQUEST,
                  "Field \"book_name\" is required"
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

        if(!isset($files['book_pic'])){
             echo ApiHelper::api(
                  ApiHelper::HTTP_STATUS_BAD_REQUEST,
                  "Field \"book_pic\" is required"
             );
            return;
        }

        if(!isset($post['description'])){
             echo ApiHelper::api(
                  ApiHelper::HTTP_STATUS_BAD_REQUEST,
                  "Field \"description\" is required"
             );
            return;
        }

        $book_name = $post['book_name'];
        $author_id = $post['author_id'];
        $book_pic = $files['book_pic'];
        $description = $post['description'];

        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_OK,
            Database::createBook(
                $book_name, $author_id, $book_pic, $description)
        );
    } else {
        echo ApiHelper::api(
            ApiHelper::HTTP_STATUS_BAD_REQUEST,
            "Route allows POST method only"
        );
    }
}