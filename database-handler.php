<?php

// database is built on SQL


// these are hardcoded credentials for now, advise moving to .env file later in project

$db_host = "localhost"; // host name for db server
$db_port = 3306; // port for db server
$db_name = "spacetech"; // database name
$db_username = "username"; // username for accessing db
$db_password = "password"; // password for accessing db

$db_connection = null;

private function createDatabaseConnection() {

    // check if database connectoin is already established

    if($db_connection !== null) {
        $db_connection = new mysqli($db_host, $db_username, $db_password, $db_name);
    }

    // if a connection is already established, continue on to ensure
    // that connection object is successfully connected

    if($db_connection->connect_error) {
        return $db_connection->connect_error;
    } else {
        return "OK";
    }

}

private function destroyDatabaseConnection() {
    if($db_connection !== null) {
        $db_connection -> close();
    }
    $db_connection == null;
}

?>