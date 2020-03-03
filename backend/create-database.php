<?php

require "..\helpers\Database.php";
require "..\helpers\ApiHelper.php";


return ApiHelper::api(
    ApiHelper::HTTP_STATUS_BAD_REQUEST,
    Database::createDatabase()
);




