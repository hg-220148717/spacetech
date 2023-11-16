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
        if($this->db_connection !== null) {$this->destroyDatabaseConnection();}
        return $msg;
    }

    public function checkSetup() {
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
        $sql_setup_commands = [
            "CREATE TABLE `users` (
                `user_id` integer PRIMARY KEY,
                `user_email` string(50) NOT NULL,
                `user_passwordhash` varchar(255) NOT NULL,
                `user_name` string(50) NOT NULL,
                `user_isstaff` boolean NOT NULL DEFAULT false,
                `user_isadmin` boolean NOT NULL DEFAULT false
              );",
              
              "CREATE TABLE `categories` (
                `category_id` integer PRIMARY KEY,
                `category_name` string(50) NOT NULL,
                `category_isdisabled` boolean NOT NULL DEFAULT false,
                `category_image` varchar(255)
              );",
              
              "CREATE TABLE `products` (
                `product_id` integer PRIMARY KEY,
                `category_id` interger NOT NULL,
                `product_name` string(75) NOT NULL,
                `product_desc` text NOT NULL,
                `product_price` decimal(6,2) NOT NULL,
                `product_stockcount` integer NOT NULL DEFAULT 0,
                `product_isdisabled` boolean NOT NULL DEFAULT false
              );",
              
              "CREATE TABLE `reviews` (
                `review_id` integer PRIMARY KEY,
                `review_userid` integer NOT NULL,
                `review_productid` integer NOT NULL,
                `review_rating` integer NOT NULL COMMENT 'Constrain input to only allow 1 to 5 stars',
                `review_text` text NOT NULL
              );",
              
              "CREATE TABLE `basket_entries` (
                `basket_entry_id` integer PRIMARY KEY,
                `basket_userid` integer NOT NULL,
                `basket_productid` integer NOT NULL,
                `entry_quanitity` integer NOT NULL DEFAULT 1,
                `entry_subtotal` decimal(6,2) NOT NULL
              );",
              
              "CREATE TABLE `orders` (
                `order_id` integer PRIMARY KEY,
                `order_userid` integer NOT NULL,
                `order_address` text NOT NULL,
                `order_comments` text NOT NULL,
                `order_total` decimal(6,2) NOT NULL,
                `order_ispaid` boolean NOT NULL DEFAULT true,
                `order_status` integer NOT NULL
              );",
              
              "CREATE TABLE `order_status` (
                `status_id` integer PRIMARY KEY,
                `status_name` string(20) NOT NULL,
                `status_colour` string(7) NOT NULL COMMENT 'Hex colour value'
              );",
              
              "CREATE TABLE `order_items` (
                `line_id` integer PRIMARY KEY,
                `order_id` integer NOT NULL,
                `product_id` integer NOT NULL,
                `line_quantity` integer NOT NULL,
                `line_subtotal` decimal(6,2) NOT NULL
              );",
              
              "CREATE TABLE `returns` (
                `return_id` integer PRIMARY KEY,
                `return_customer_id` integer NOT NULL,
                `return_order_id` integer NOT NULL,
                `return_line_id` integer NOT NULL,
                `return_reason` text NOT NULL,
                `return_status` integer NOT NULL
              );",
              
              "ALTER TABLE `products` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);",
              
              "ALTER TABLE `reviews` ADD FOREIGN KEY (`review_userid`) REFERENCES `users` (`user_id`);",
              
              "ALTER TABLE `reviews` ADD FOREIGN KEY (`review_productid`) REFERENCES `products` (`product_id`);",
              
              "ALTER TABLE `basket_entries` ADD FOREIGN KEY (`basket_userid`) REFERENCES `users` (`user_id`);",
              
              "CREATE TABLE `products_basket_entries` (
                `products_product_id` integer,
                `basket_entries_basket_productid` integer,
                PRIMARY KEY (`products_product_id`, `basket_entries_basket_productid`)
              );",
              
              "ALTER TABLE `products_basket_entries` ADD FOREIGN KEY (`products_product_id`) REFERENCES `products` (`product_id`);",
              
              "ALTER TABLE `products_basket_entries` ADD FOREIGN KEY (`basket_entries_basket_productid`) REFERENCES `basket_entries` (`basket_productid`);",
              
              
              "ALTER TABLE `orders` ADD FOREIGN KEY (`order_userid`) REFERENCES `users` (`user_id`);",
              
              "ALTER TABLE `order_status` ADD FOREIGN KEY (`status_id`) REFERENCES `orders` (`order_status`);",
              
              "ALTER TABLE `order_items` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);",
              
              "ALTER TABLE `order_items` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);",
              
              "ALTER TABLE `returns` ADD FOREIGN KEY (`return_customer_id`) REFERENCES `users` (`user_id`);",
              
              "ALTER TABLE `returns` ADD FOREIGN KEY (`return_order_id`) REFERENCES `orders` (`order_id`);",
              
              "ALTER TABLE `returns` ADD FOREIGN KEY (`return_line_id`) REFERENCES `order_items` (`line_id`);",
              
              "ALTER TABLE `returns` ADD FOREIGN KEY (`return_status`) REFERENCES `order_status` (`status_id`);"


        ];
        if($this->createDatabaseConnection() == "OK") {
            foreach ($sql_setup_commands as $command) {
                $result = $this->db_connection->execute_query($command);
            }
        }
    }

}

$db_handler = new Database();
echo $db_handler->testDatabaseConnection();
echo $db_handler->checkSetup();

?>