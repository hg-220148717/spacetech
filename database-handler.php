<?php

// database is built on SQL

Class Database {

// these are hardcoded credentials for now, advise moving to .env file later in project

    private $db_host = "localhost"; // host name for db server
    private $db_port = 3306; // port for db server
    private $db_name = "spacetech_dev"; // database name
    private $db_username = "spacetech_dev"; // username for accessing db
    private $db_password = "m@tz%H83dtS4#XS"; // password for accessing db

    private $db_connection = null;

    private function createDatabaseConnection() {

        // check if database connectoin is already established

        if($this->db_connection === null) {
            $this->db_connection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name, $this->db_port);
        }

        // if a connection is already established, continue on to ensure
        // that connection object is successfully connected

        $this->testDatabaseConnection();

    }

    private function destroyDatabaseConnection() {
        if($this->db_connection !== null) {
            $this->db_connection -> close();
        }
        $this->db_connection == null;
    }

    public function testDatabaseConnection() {
        if($this->db_connection === null) {
            $this->createDatabaseConnection();
        }

        if($this->db_connection->connect_error) {
            $msg = "Connection Error: " . htmlspecialchars($this->db_connection->connect_error, ENT_QUOTES);
        } else {
            $msg = "OK";
        }

        $this->destroyDatabaseConnection();
        return $msg;
    }

    private function checkSetup() {
        if($this->createDatabaseConnection() == "OK") {

            $result = $this->db_connection->execute_query("SELECT 1 FROM `users` LIMIT 1");
            foreach($result as $row) {
                if($row == null) {
                    // setup has not occurred, default superadmin user does not exist
                    // trigger creation of database tables
                    $this->runSetup();
                    return true;
                } else {
                    return true; // setup has already occurred
                }
            }
        }
    }

    private function runSetup() {
        // todo
    }

}

$db_handler = new Database();
echo $db_handler->testDatabaseConnection();

?>