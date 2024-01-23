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

        return $this->testDatabaseConnection();

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
        //$this->destroyDatabaseConnection();
        return $msg;
    }

    public function checkSetup() {
        if($this->createDatabaseConnection() !== "OK") {
          return true; // setup has already occurred
        }

        try{
          $this->db_connection->execute_query("SELECT 1 FROM `users` LIMIT 1;");
        } catch(mysqli_sql_exception $e) {
          // setup has not occurred, users table does not exist
          // trigger creation of database tables
          $this->runSetup();
          return true;
        }
        
    }
        
    private function generatePasswordHash($password) {
      return password_hash($password, PASSWORD_DEFAULT);
    }

    private function checkPassword($input, $hash) {
      return password_verify($input, $hash);
    }

    private function runSetup() {
        $sql_setup_commands = ["CREATE TABLE `users` (
            `user_id` integer PRIMARY KEY,
            `user_email` varchar(50) NOT NULL UNIQUE,
            `user_passwordhash` varchar(255) NOT NULL,
            `user_name` varchar(50) NOT NULL,
            `user_isstaff` boolean NOT NULL DEFAULT false,
            `user_isadmin` boolean NOT NULL DEFAULT false
          );",

          "ALTER TABLE `users` CHANGE `user_id` `user_id` INT(11) NOT NULL AUTO_INCREMENT;",
          
          "CREATE TABLE `categories` (
            `category_id` integer PRIMARY KEY,
            `category_name` varchar(50) NOT NULL,
            `category_isdisabled` boolean NOT NULL DEFAULT false,
            `category_image` varchar(255)
          );",
          
          "CREATE TABLE `products` (
            `product_id` integer PRIMARY KEY,
            `category_id` integer NOT NULL,
            `product_name` varchar(75) NOT NULL,
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
            `status_name` varchar(20) NOT NULL,
            `status_colour` varchar(7) NOT NULL COMMENT 'Hex colour value'
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
          
          "ALTER TABLE `basket_entries` ADD FOREIGN KEY (`basket_productid`) REFERENCES `products` (`product_id`);",
          
          "ALTER TABLE `orders` ADD FOREIGN KEY (`order_userid`) REFERENCES `users` (`user_id`);",
          
          "ALTER TABLE `orders` ADD FOREIGN KEY (`order_status`) REFERENCES `order_status` (`status_id`);",
          
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

    public function createUser($email, $password, $name) {
      if($this->createDatabaseConnection() == "OK") {
        try {
          $result = $this->db_connection->execute_query("SELECT user_email FROM `users` WHERE `user_email` LIKE ?", [$email]);
          if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc() ) {
              if(strtolower($row["user_email"]) == $email) {
                return "Error - supplied email address already in use.";
              }
            }
          } else {
              $passhash = $this->generatePasswordHash($password);
              $this->db_connection->execute_query("INSERT INTO `users` (`user_email`, `user_passwordhash`, `user_name`) VALUES (?,?,?);", [$email, $passhash, $name]);
              return "User account created successfully.";
          }
        } catch (Exception $e) {
          return "An error occurred. Stack trace: " . $e;
        }
      }
    }

    public function checkCredentials($email, $password) {
      if($this->createDatabaseConnection() == "OK") {
        try {
          $result = $this->db_connection->execute_query("SELECT * FROM `users` WHERE `user_email` LIKE ?;", [$email]);
          if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc() ) {
              if(strtolower($row["user_email"]) == $email) {
                if($this->checkPassword($password, $row["user_passwordhash"])) {
                  return $row["user_id"];
                } else {
                  return "Invalid username or password.";
                }
              }
            }
          } else {
            return "Invalid username or password.";
          }
        } catch (Exception $e) {
          return "An error occurred. Stack trace: " . $e;
        }
      }
    }

    public function getAllProducts($includeDisabledProducts) {
      if($this->createDatabaseConnection() == "OK") {

        $output = array();

        try {
          if($includeDisabledProducts) {
            $result = $this->db_connection->execute_query("SELECT * FROM `products`;");
          } else { 
            $result = $this->db_connection->execute_query("SELECT * FROM `products` WHERE `product_isdisabled` = FALSE;");
          }

          while ($row = $result->fetch_assoc() ) {
            if($result->num_rows > 0) {
              $product = array();
              $product["product_id"] = $row["product_id"];
              $product["category_id"] = $row["category_id"];
              $product["product_name"] = $row["product_name"];
              $product["product_desc"] = $row["product_desc"];
              $product["product_price"] = $row["product_price"];
              $product["product_stockcount"] = $row["product_stockcount"];
              $product["product_isdisabled"] = $row["product_isdisabled"];
              $output[] = $output + $product;

            } else {
              break;
            }
          }


        } catch(Exception $e) {
          return "An error occurred. Stack trace: " . $e;
        }

        return $output;
      }
    }

    public function getAllCategories($includeDisabledCategories) {
      if($this->createDatabaseConnection() == "OK") {

        $output = array();

        try {
          if($includeDisabledCategories) {
            $result = $this->db_connection->execute_query("SELECT * FROM `categories`;");
          } else { 
            $result = $this->db_connection->execute_query("SELECT * FROM `categories` WHERE `category_isdisabled` = FALSE;");
          }

          while ($row = $result->fetch_assoc() ) {
            if($result->num_rows > 0) {
              $category = array();
              $category["category_id"] = $row["category_id"];
              $category["category_name"] = $row["category_name"];
              $category["category_isdisabled"] = $row["category_isdisabled"];
              $category["category_image"] = $row["category_image"];
            
              $output[] = $output + $category;

            } else {
              break;
            }
          }


        } catch(Exception $e) {
          return "An error occurred. Stack trace: " . $e;
        }

        return $output;
      }
    }

    public function getProductByID($id) {
      if(is_int($id)) {
        if($this->createDatabaseConnection() == "OK") {
          try {
            $result = $this->db_connection->execute_query("SELECT * FROM `products` WHERE `product_id` = ? LIMIT 1;", [$id]);

            while ($row = $result->fetch_assoc() ) {
              if($result->num_rows > 0) {
                $product = array();
                $product["product_id"] = $row["product_id"];
                $product["category_id"] = $row["category_id"];
                $product["product_name"] = $row["product_name"];
                $product["product_desc"] = $row["product_desc"];
                $product["product_price"] = $row["product_price"];
                $product["product_stockcount"] = $row["product_stockcount"];
                $product["product_isdisabled"] = $row["product_isdisabled"];
              
                return $product;
  
              } else {
                return "Error - No results found.";
              }
            }

          } catch(Exception $e) {
            return "An error occurred. Stack trace: " . $e;
          }

        }
      } else {
        return "Error - ID must be an integer";
      }
    }

    public function getProductsByName($inputted_name) {
      if(is_string($inputted_name)) {
        if($this->createDatabaseConnection() == "OK") {

          $output = array();

          try {
            $result = $this->db_connection->execute_query("SELECT * FROM `products` WHERE `product_name` = '%?%' ORDER BY `product_name`;", [$inputted_name]);

            while ($row = $result->fetch_assoc() ) {
              if($result->num_rows > 0) {
                $product = array();
                $product["product_id"] = $row["product_id"];
                $product["category_id"] = $row["category_id"];
                $product["product_name"] = $row["product_name"];
                $product["product_desc"] = $row["product_desc"];
                $product["product_price"] = $row["product_price"];
                $product["product_stockcount"] = $row["product_stockcount"];
                $product["product_isdisabled"] = $row["product_isdisabled"];
              
                $output[] = $output + $product;
  
              } else {
                return "Error - No results found.";
              }
            }

          } catch(Exception $e) {
            return "An error occurred. Stack trace: " . $e;
          }

        }
      } else {
        return "Error - input must be a string";
      }
    }

    public function getProductsByCategoryID($category_id) {
      if(is_int($category_id)) {
        if($this->createDatabaseConnection() == "OK") {
          try {
            $result = $this->db_connection->execute_query("SELECT * FROM `products` WHERE `category_id` = ?;", [$category_id]);
            $output = array();
            while ($row = $result->fetch_assoc() ) {
              if($result->num_rows > 0) {
                $product = array();
                $product["product_id"] = $row["product_id"];
                $product["category_id"] = $row["category_id"];
                $product["product_name"] = $row["product_name"];
                $product["product_desc"] = $row["product_desc"];
                $product["product_price"] = $row["product_price"];
                $product["product_stockcount"] = $row["product_stockcount"];
                $product["product_isdisabled"] = $row["product_isdisabled"];
              
                $output[] = $output + $product;
  
              } else {
                return "Error - No results found.";
              }
            }

            return $output;

          } catch(Exception $e) {
            return "An error occurred. Stack trace: " . $e;
          }

        }
      } else {
        return "Error - ID must be an integer";
      }
    }

    public function createCategory($name, $is_disabled, $image_path) {
      if($this->createDatabaseConnection() == "OK") {
        try {
          $this->db_connection->execute_query("INSERT INTO `categories` (`category_name`, `category_isdisabled`, `category_image`) VALUES (?,?,?);", [$name, $is_disabled, $image_path]);
          return "Category created successfully.";
        } catch(Exception $e) {
          return "An error occurred. Stack trace: " . $e;
        }
    } else {
      return "An error occurred.";
    }
  }

  public function createProduct($name, $category_id, $desc, $price, $stockcount, $is_disabled) {
    if($this->createDatabaseConnection() == "OK") {
      try {
        $result = $this->db_connection->execute_query("SELECT `category_id` FROM `categories` WHERE `category_id` LIKE ?", [$category_id]);
        if($result->num_rows <= 0) {
          return "Category ID invalid.";
        }

        $this->db_connection->execute_query("INSERT INTO `products` (`product_name`, `category_id`, `product_desc`, `product_price`, `product_stockcount`, `product_isdisabled`) VALUES (?,?,?,?,?,?);", [$name, $category_id, $desc, $price, $stockcount, $is_disabled]);
        return "Product created successfully.";

      } catch(Exception $e) {
        return "An error occurred. Stack trace: " . $e;
      }
  } else {
    return "An error occurred.";
  }
}

  public function getEmailFromUserID($id) {

    // check if supplied user ID is an integer
    if(!is_int($id)) {
      return "Error - ID must be an integer";
    }

    // check db connection is active, error if not
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - database connection failure.";
    }

    try {
      // query the users table for the supplied user ID, limiting results to 1 entry to prevent errors
      $result = $this->db_connection->execute_query(
        "SELECT `user_email` FROM `users` WHERE `user_id` = ? LIMIT 1;", [$id]
      );
    
      // check if the db returned at least 1 user entry
      if($result->num_rows <= 0) {
        return "Error - user ID not found.";
      }

      // loop through returned data from db
      while($row = $result->fetch_assoc()) {
        // return user's email address from queried user ID
        return $row["user_email"];
      }

    } catch(Exception $e) {
      return "Error - database query failure.";
    }
  }

  public function getNameFromUserID($id) {
    if(is_int($id)) {
      if($this->createDatabaseConnection() == "OK") {
        try {
          $result = $this->db_connection->execute_query("SELECT `user_name` FROM `users` WHERE `user_id` = ? LIMIT 1;", [$id]);

          while ($row = $result->fetch_assoc() ) {
            if($result->num_rows > 0) {
              return $row["user_name"];
            } else {
              return "Error - No results found.";
            }
          }

        } catch(Exception $e) {
          return "An error occurred. Stack trace: " . $e;
        }

      }
    } else {
      return "Error - ID must be an integer";
    }
  }

  public function addToBasket($user_id, $product_id, $qty, $subtotal) {
    if(is_int($user_id) && is_int($product_id) && is_int($qty)) {
      if($this->createDatabaseConnection() == "OK") {
        try {
          $result = $this->db_connection->execute_query("INSERT INTO `basket_entries` (`basket_userid`, `basket_productid`, `entry_quanitity`, `entry_subtotal`) VALUES (?,?,?,?);", [$user_id, $product_id, $qty, $subtotal]);
          return "Added to cart.";
        } catch(Exception $e) {
          return "An error occurred. Stack trace: " . $e;
        }
      }
    } else {
      return "Error - user id, product id or qty is not a number.";
    }
  }

  public function removeFromBasket($entry_id, $user_id) {
    if(is_int($user_id) && is_int($entry_id) ) {
      if($this->createDatabaseConnection() == "OK") {
        try {
          $result = $this->db_connection->execute_query("DELETE FROM basket_entries WHERE `basket_entry_id` = ? AND basket_userid = ?", [$entry_id, $user_id]);
          return "Removed from cart.";
        } catch(Exception $e) {
          return "An error occurred. Stack trace: " . $e;
        }
      }
    } else {
      return "Error - user id or entry id is not a number.";
    }
  }

  public function submitOrder($user_id, $address, $comments, $total, $is_paid) {
    if(is_int($user_id)) {
      if($this->createDatabaseConnection() == "OK") {
        try {

          $result = $this->db_connection->execute_query("INSERT INTO `orders` (`order_userid`, `order_address`, `order_comments`, `order_total`, `order_ispaid`, `order_status`) VALUES (?, ?, ?, ?, ?, '1');", [$user_id, $address, $comments, $total, $is_paid]);
          if($result === TRUE) {
            $order_no = $this->db_connection->insert_id;
          } else {
            return "An error occurred creating the order.";
          }

          $basket_contents = $this->getBasketContents($user_id);
          foreach ($basket_contents as $item) {
            $result = $this->db_connection->execute_query("INSERT INTO `order_items` (`order_id`, `product_id`, `line_quantity`, `line_subtotal`) VALUES (?, ?, ?, ?)", [$order_no, $item["product_id"], $item["qty"], $item["subtotal"]]);
            $this->removeFromBasket($item["entry_id"], $user_id);
          }

        } catch(Exception $e) {
          return "An error ocurred. Stack trace - " . $e;
        }
      } else {
        return "An error occurred.";
      }
    } else {
      return "Error - user id must be an int";
    }
  }

  public function getBasketCount($user_id) {
    if(is_int($user_id)) {
      if($this->createDatabaseConnection() == "OK") {
        try{
          $result = $this->db_connection->execute_query("SELECT COUNT(`basket_userid`) FROM `basket_entries` WHERE `basket_userid` = ?;", [$user_id]);

          while ($row = $result->fetch_assoc() ) {
            if($result->num_rows > 0) {
              return $row["COUNT(`basket_userid`)"];
            } else {
              return 0;
            }
          }
        } catch(Exception $e) {
          return "An error occurred - stack trace: " . $e;
        }
      }
    } else {
      return "Error - used id is not a number.";
    }
  }

  public function getBasketTotal($user_id) {
    if(is_int($user_id)) {
      if($this->createDatabaseConnection() == "OK") {
        try{
          $result = $this->db_connection->execute_query("SELECT SUM(`entry_subtotal`) FROM `basket_entries` WHERE `basket_userid` = ?;", [$user_id]);

          while ($row = $result->fetch_assoc() ) {
            if($result->num_rows > 0) {
              return $row["SUM(`entry_subtotal`)"];
            } else {
              return 0.00;
            }
          }
        } catch(Exception $e) {
          return "An error occurred - stack trace: " . $e;
        }
      }
    } else {
      return "Error - used id is not a number.";
    }
  }

  public function getBasketContents($user_id) {
    if(is_int($user_id)) {
      if($this->createDatabaseConnection() == "OK") {
        try {
          $result = $this->db_connection->execute_query("SELECT * FROM `basket_entries` WHERE `basket_userid` = ?;", [$user_id]);

          $basket = array();

          while ($row = $result->fetch_assoc() ) {
            if($result->num_rows > 0) {
             
              $basket_entry = array();

              $basket_entry["entry_id"] = $row["basket_entry_id"];
              $basket_entry["product_id"] = $row["basket_productid"];
              $basket_entry["qty"] = $row["entry_quanitity"];
              $basket_entry["subtotal"] = $row["entry_subtotal"];

              $basket[] = $basket + $basket_entry;

            }
          }

          return $basket;

        } catch(Exception $e) {
          return "An error occurred. Stack trace - ". $e;
        }
      } else {
        return "An error occurred.";
      }
    } else {
      return "Error - user ID must be an int.";
    }
  }

    
}

$db_handler = new Database();

$db_handler->getAllProducts(true);

//echo $db_handler->testDatabaseConnection();
//echo $db_handler->checkSetup();
//echo $db_handler->createUser("220148717@aston.ac.uk", "password", "Harrison");



?>