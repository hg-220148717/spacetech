<?php

// database is built on SQL


// these are hardcoded credentials for now, advise moving to .env file later in project

$db_host = "localhost"; // host name for db server
$db_port = 3306; // port for db server
$db_name = "spacetech"; // database name
$db_username = "username"; // username for accessing db
$db_password = "password"; // password for accessing db

$db_connection = null;

function createDatabaseConnection() {

    if($db_connection === null) {
        // connection already established, some kind of handling needs to be done here
    } else {
        $db_connection = new mysqli($db_host, $db_username, $db_password, $db_name);

        if($db_connection->connect_error) {
            // connection failed - error message stored in $db_connection->connect_error
        } else {
            // connection successful
        }

    }


}

?>