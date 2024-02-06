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

    private static $ERROR_MSG_INPUT_VALIDATION = "Input validation failed.";
    private static $ERROR_MSG_DB_CONNECTION_FAILED = "Database connection error.";
    private static $ERROR_MSG_DB_QUERY_EXCEPTION = "Database query error.";


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

      // check if database connection is active, close if currently active
      if($this->db_connection !== null) {
          $this->db_connection->close();
      }

      // set variable to null 
      // TODO - check if variable is exists / is not currently set to null
      $this->db_connection == null;
    }


    /**
     * Tests the connection to the database.
     * 
     * `Database error.` - database has not been initialised
     * 
     * `Connection Error.` - connection has been initialised but an error has occurred.
     * 
     * `OK` - database is initialised, connection is good & is ready to use.
     * 
     * @return string - Returns a string indicating the connection status.
     */
    public function testDatabaseConnection() {
      // check if database connection has been established yet
        if($this->db_connection === null) {
          // this would have caused an infinite loop if ever there was an error creating the database connection
          // $this->createDatabaseConnection();
          return "Database error.";
        }
        // set default placeholder for connection check
        $msg = "Checking connection...";

        if($this->db_connection->connect_error) {
            $msg = "Connection Error: " . htmlspecialchars($this->db_connection->connect_error, ENT_QUOTES);
        } else {
            $msg = "OK";
        }

        // return connection status
        return $msg;
    }

    /**
     * Check the database is correctly setup.
     * 
     * @return boolean status - returns `true` if database is correctly setup.
     * 
     */
    public function checkSetup() {
        if($this->createDatabaseConnection() !== "OK") {
          return true; // setup has already occurred
        }

        try{
          $this->db_connection->execute_query("SELECT 1 FROM `users` LIMIT 1;");
          return true;
        } catch(mysqli_sql_exception $e) {
          // setup has not occurred, users table does not exist
          // trigger creation of database tables
          $this->runSetup();
          return true;
        }
        
    }
        
    /**
     * Generates a password hash for a desired password.
     * 
     * @param string $password Input password to be hashed.
     * 
     * @return string - Returns the password hash 
     * 
     */
    private function generatePasswordHash($password) {
      return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Checks an inputted password against a hash to confirm if it matches.
     * 
     * @param string $input - Inputted password to check against hash
     * @param string $hash - Hash to compare password to
     * 
     * @return boolean - Returns `true` if password matches, returns `false` if doesn't match.
     */
    private function checkPassword($input, $hash) {
      return password_verify($input, $hash);
    }

    /**
     * Runs setup procedure of creating necessary tables & sets up required relationships.
     * 
     * @return void
     * 
     */
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

    /**
     * Creates a user given an email address, desired password & name.
     * Stores the user data in the database.
     * 
     * @param string $email - User's email address
     * @param string $password - User's desired password
     * @param string $name - User's name
     * 
     * @return string - Returns an error if there was an issue creating the user,
     * or returns a confirmation message if created successfully.
     * 
     */
    public function createUser($email, $password, $name) {

      // input validation
      if(!is_string($email) || !is_string($password) || !is_string($name)) {
        return $this->ERROR_MSG_INPUT_VALIDATION;
      }

      // check db connection
      if($this->createDatabaseConnection() !== "OK") {
        return $this->ERROR_MSG_DB_CONNECTION_FAILED;
      }

      try {
        $result = $this->db_connection->execute_query("SELECT user_email FROM `users` WHERE `user_email` LIKE ?", [$email]);
        
        if($result->num_rows > 0) {
          // email already exists in users table
          return "Error - email already in use.";
        }

        $passhash = $this->generatePasswordHash($password);
        $this->db_connection->execute_query("INSERT INTO `users` (`user_email`, `user_passwordhash`, `user_name`) VALUES (?,?,?);", [$email, $passhash, $name]);
        return "User account created successfully.";

      } catch(Exception $e) {
        return $this->ERROR_MSG_DB_QUERY_EXCEPTION;
      }
    }

    /**
     * Checks & validates given credentials against database of users
     * 
     * @param string $email - User's inputted email
     * @param string $password - User's inputted password
     * 
     * @return string|int - Returns user's ID if successful, returns error message if unsuccessful.
     * 
     */
    public function checkCredentials($email, $password) {

      // check db connection
      if($this->createDatabaseConnection() !== "OK") {
        return "Error - database connection error.";
      }   
      
      try {
        $result = $this->db_connection->execute_query("SELECT * FROM `users` WHERE `user_email` LIKE ?;", [$email]);
        
        // check if user found in database
        if($result->num_rows <= 0) {
          return "Incorrect credentials.";
        }

        while ($row = $result->fetch_assoc() ) {

          // check if email matches entry, if it doesn't, go to next element in list of results
          if(strtolower($row["user_email"]) !== strtolower($email)) {
            continue;
          }

          if($this->checkPassword($password, $row["user_passwordhash"])) {
            return $row["user_id"];
          }

        }

        // if no entries match, credentials are wrong, return error.
        return "Incorrect credentials.";
        }
      }

    /**
     * Return information about all products
     * @param $includeDisabledProducts - Include disabled products in results? (Y/N)
     * @return array|string Returns array of products if sucessful, or error message if unsuccessful
     */
    public function getAllProducts($includeDisabledProducts) {

      // input validation
      if(!is_bool($includeDisabledProducts)) {
        return "Error - parameter must be a boolean.";
      }

      // check db connection
      if($this->createDatabaseConnection() !== "OK") {
        return "Error - database connection error.";
      }
      
      /** inline if statement to modify SQL query executed to add additional
       * `where` clause subject to status of var $includeDisabledCategories
       * --
       * if true - does not include where statement which excludes disabled categories
       * if false - includes where statement to exclude disabled categories
       */
      $sql_query = "SELECT * FROM `products`" . (($includeDisabledProducts) ? "" : " WHERE `product_isdisabled` = FALSE") . ";";

      $products_array = array();

      try {
        $result = $this->db_connection->execute_query($sql_query);

        if($result->num_rows <= 0) {
          // no products found, return empty array
          return $products_array;
        }

        while ($row = $result->fetch_assoc() ) {

          // Refactored the below. Copied the resulting $row from the db,
          // rather than iterating through each key, making a temp array and then appending temp array.
          /*

          $product = array();
          $product["product_id"] = $row["product_id"];
          $product["category_id"] = $row["category_id"];
          $product["product_name"] = $row["product_name"];
          $product["product_desc"] = $row["product_desc"];
          $product["product_price"] = $row["product_price"];
          $product["product_stockcount"] = $row["product_stockcount"];
          $product["product_isdisabled"] = $row["product_isdisabled"];
          
          */
          $products_array[] = $products_array + $row;

        }

        return $products_array;

      } catch(Exception $e) {
        return "Error - database query error";
      }

    }

    /**
     * Return information about all categories
     * @param $includeDisabledCategories - Include disabled categories in results? (Y/N)
     * @return array|string Returns array of categories if sucessful, or error message if unsuccessful
     */
    public function getAllCategories($includeDisabledCategories) {
      
      // input validation
      if(!is_bool($includeDisabledCategories)) {
        return "Error - parameter must be a boolean.";
      }

      // check db connection
      if($this->createDatabaseConnection() !== "OK") {
        return "Error - database connection error.";
      }

      /** inline if statement to modify SQL query executed to add additional
       * `where` clause subject to status of var $includeDisabledCategories
       * --
       * if true - does not include where statement which excludes disabled categories
       * if false - includes where statement to exclude disabled categories
       */
      $sql_query = "SELECT * FROM `categories`" . (($includeDisabledCategories) ? "" : " WHERE `category_isdisabled` = FALSE") . ";";

      try {
        $result = $this->db_connection->execute_query($sql_query);
        
        $categories_array = array();

        if($result->num_rows <= 0) {
          // no categories found, return blank array
          return $categories_array; 
        }

        // loop through results
        while ($row = $result->fetch_assoc() ) {
      
          // Refactored the below. Copied the resulting $row from the db,
          // rather than iterating through each key, making a temp array and then appending temp array.
          
          /*
          $category = array();
          $category["category_id"] = $row["category_id"];
          $category["category_name"] = $row["category_name"];
          $category["category_isdisabled"] = $row["category_isdisabled"];
          $category["category_image"] = $row["category_image"];
          */
        
          $categories_array[] = $categories_array + $row;
        }

        // return array with all category data returned from database
        return $categories_array;

      } catch(Exception $e) {
        return "Error - Database query error.";
      }


    }

    /**
     * Get product details from product ID.
     * @param $id Product ID
     * @return null|string|array
     * Returns null if product not found.
     * Returns error message if something went wrong.
     * Returns array of product details if successful.
     */
    public function getProductByID($id) {

      // input validation
      if(!is_int($id)) {
        return "Error - ID must be an integer";
      }

      // check db connection
      if($this->createDatabaseConnection() !== "OK") {
        return "Error - database connection error.";
      }

      try {

        $result = $this->db_connection->execute_query("SELECT * FROM `products` WHERE `product_id` = ? LIMIT 1;", [$id]);

        if($result->num_rows <= 0) {
          // product not found, return null.
          return null;
        }

        while ($row = $result->fetch_assoc() ) {
          // Refactored the below. Copied the resulting $row from the db,
          // rather than iterating through each key, then returning an array.
          /*  
            $product = array();
            $product["product_id"] = $row["product_id"];
            $product["category_id"] = $row["category_id"];
            $product["product_name"] = $row["product_name"];
            $product["product_desc"] = $row["product_desc"];
            $product["product_price"] = $row["product_price"];
            $product["product_stockcount"] = $row["product_stockcount"];
            $product["product_isdisabled"] = $row["product_isdisabled"];
          */
          return $row;

        }

      } catch(Exception $e) {
        return "Error - database query error.";
      }

      
    }

    /**
     * Get product details by name.
     * @param $inputted_name Inputted name to search for.
     * @return null|string|array
     * Returns null if product not found.
     * Returns string if something went wrong (error message).
     * Returns array of products if successful, ordered by product name.
     */
     public function getProductsByName($inputted_name) {

      // input validation
      if(!is_string($inputted_name)) {
        return "Error - inputted name must be a string.";
      }

      // check db connection
      if($this->createDatabaseConnection() !== "OK") {
        return "Error - database connection error.";
      }

      $products_array = array();

      try {
        $result = $this->db_connection->execute_query("SELECT * FROM `products` WHERE `product_name` = '%?%' ORDER BY `product_name`;", [$inputted_name]);

        if($result->num_rows <= 0) {
          // no products found, return null.
          return null;
        }

        while ($row = $result->fetch_assoc() ) {
          // Refactored this mess.
          /*
          $product = array();
          $product["product_id"] = $row["product_id"];
          $product["category_id"] = $row["category_id"];
          $product["product_name"] = $row["product_name"];
          $product["product_desc"] = $row["product_desc"];
          $product["product_price"] = $row["product_price"];
          $product["product_stockcount"] = $row["product_stockcount"];
          $product["product_isdisabled"] = $row["product_isdisabled"];
        
          $output[] = $output + $product;
          */
          $products_array[] = $products_array + $row;
        }

        return $products_array;


      } catch(Exception $e) {
        return "Error - database query error.";
      }
    }

    /**
     * Get an array of products belonging to a particular category from Category ID.
     * @author H. Green (2024)
     * @param $category_id Category ID
     * @return null|string|array
     * Returns null if no products found.
     * Returns string (error message) if something went wrong
     * Returns array of products if successful.
     */
    public function getProductsByCategoryID($category_id) {

      // input validation
      if(!is_int($category_id)) {
        return $this->ERROR_MSG_INPUT_VALIDATION;
      }

      // check db connection
      if($this->createDatabaseConnection() !== "OK") {
        return $this->ERROR_MSG_DB_CONNECTION_FAILED;
      }

      try {
        $result = $this->db_connection->execute_query("SELECT * FROM `products` WHERE `category_id` = ?;", [$category_id]);
           
        if($result->num_rows <= 0) {
          // no results found, category contains no products, return null
          return null;
        }

        $products_array = array();

        while($row = $result->fetch_assoc() ) {

          // Refactored this mess again.
          /*
          $product = array();
          $product["product_id"] = $row["product_id"];
          $product["category_id"] = $row["category_id"];
          $product["product_name"] = $row["product_name"];
          $product["product_desc"] = $row["product_desc"];
          $product["product_price"] = $row["product_price"];
          $product["product_stockcount"] = $row["product_stockcount"];
          $product["product_isdisabled"] = $row["product_isdisabled"];
          $output[] = $output + $product;
  
          */

          $products_array[] = $products_array + $row;
        
        }

        return $products_array;
      } catch(Exception $e) {
        return $this->ERROR_MSG_DB_QUERY_EXCEPTION;
      }

    }

    /**
     * Create a new category from supplied parameters
     * @param $name Product name
     * @param $is_disabled Is the category disabled?
     * @param $image_path Local path to category cover image
     * 
     * @return string Status message (error or success).
     * 
     */
    public function createCategory($name, $is_disabled, $image_path) {

      // check database connection
      if($this->createDatabaseConnection() !== "OK") {
        return "Error - database connection error.";
      }

      // attempt to insert new category info into database
      try {
        $this->db_connection->execute_query("INSERT INTO `categories` (`category_name`, `category_isdisabled`, `category_image`) VALUES (?,?,?);", [$name, $is_disabled, $image_path]);
          return "Category created successfully.";
      } catch(Exception $e) {
        return "Error - database query error.";
      }
  }

  /**
   * Create a product from given information
   * @param $name Product Name
   * @param $category_id Category ID product belongs to
   * @param $desc Product Description
   * @param $price Product Price
   * @param $stockcount Current Stock Count
   * @param $is_disabled Is the product currently disabled?
   * 
   * @return string Status message (success or error).
   * 
   */
  public function createProduct($name, $category_id, $desc, $price, $stockcount, $is_disabled) {

    // validate function input
    if(is_string($name) && is_int($category_id) && is_string($desc) && !is_nan($price) && is_int($stockcount) && is_bool($is_disabled)) {
      return "Error - input validation failed";
    }

    // check database connection
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - Database connection error.";
    }

    // validate category ID, and attempt to add product
    try{
      $result = $this->db_connection->execute_query("SELECT `category_id` FROM `categories` WHERE `category_id` LIKE ?", [$category_id]);
      // check if supplied category ID found in DB
      if($result->num_rows <= 0) {
        return "Category ID invalid.";
      }

      $this->db_connection->execute_query("INSERT INTO `products` (`product_name`, `category_id`, `product_desc`, `product_price`, `product_stockcount`, `product_isdisabled`) VALUES (?,?,?,?,?,?);", [$name, $category_id, $desc, $price, $stockcount, $is_disabled]);
      return "Product created successfully.";


    } catch(Exception $e) {
      return "Error - database query error.";
    }
}

  /**
   * Get a user's email address from their User ID
   * @param $id - User ID
   * @return string Returns email address or error message
   * 
   */
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

    return "Error - unexpected error occurred.";

  }

  /** 
   * Get a user's name from a given user ID
   * 
   * @param int $id - User ID
   * 
   * @return string Returns the user's name or an error message.
   * 
   */
  public function getNameFromUserID($id) {

    // input validation
    if(!is_int($id)) {
      return "Error - ID must be an integer.";
    }

    // check db connection
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - database connection error";
    }

    try {
      $result = $this->db_connection->execute_query("SELECT `user_name` FROM `users` WHERE `user_id` = ? LIMIT 1;", [$id]);

      // check if user found in db
      if($result->num_rows <= 0) {
        return "Error - no results found";
      }

      // loop through db results
      while($row = $result->fetch_assoc()) {
        return $row["user_name"];
      }

      // something has gone very wrong if this is returned.
      return "Error - name not found.";
    }catch(Exception $e) {
      return "Error - database query error.";
    }


  }

  /**
   * Adds an item entry to a user's basket. Includes quantity & order line subtotal
   * 
   * @param $user_id User ID
   * @param $product_id Product ID
   * @param $qty Quantity of Product
   * @param $subtotal Order line subtotal
   * 
   * @return string Status message.
   * 
   */
  public function addToBasket($user_id, $product_id, $qty, $subtotal) {

    // input validation - check if supplied user ID is an integer
    if(!is_int($user_id)) {
      return "Error - User ID must be an integer.";
    }

    // input validation - check if supplied product ID is an integer
    if(!is_int($product_id)) {
      return "Error - Product ID must be an integer.";
    }
    
    // input validation - check if supplied quantity is an integer
    if(!is_int($qty)) {
      return "Error - Quantity must be an integer.";
    }

    // TODO - validate subtotal input     

    // check connection to database
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - Database connection error.";
    }

    // attempt to add item to basket
    try {
      $this->db_connection->execute_query(
        "INSERT INTO `basket_entries` (`basket_userid`, `basket_productid`, `entry_quanitity`, `entry_subtotal`) VALUES (?,?,?,?);", [$user_id, $product_id, $qty, $subtotal]
      );
      
      // output success message
      return "Added to cart.";
    } catch(Exception $e) {
      // catch any errors outputted whilst executing the query
      return "Error - database query error.";
    }
  }

  /**
   * Removes an entry from a basket.
   * 
   * @param int $entry_id - basket entry ID to remove
   * @param int $user_id - User ID to remove from
   * 
   * @return string Returns a message to indicate success or failure.
   * 
   */
  public function removeFromBasket($entry_id, $user_id) {

    // input validation
    if(!is_int($user_id) || !is_int($entry_id)) {
      return "Error - User ID & Basket Entry ID must be integers.";
    }

    // check db connection
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - database connection error";
    }

    try {
      // remove item entry ID from basket
      $this->db_connection->execute_query("DELETE FROM basket_entries WHERE `basket_entry_id` = ? AND basket_userid = ?", [$entry_id, $user_id]);
      return "Removed from cart.";
    }catch(Exception $e) {
      return "Error - database query error.";
    }

  }

  /**
   * Submits an order for a user
   * 
   * @param int $user_id - ID of user placing order
   * @param string $address - Address lines concatenated into 1 string
   * @param string $comments - Any comments added to the order
   * @param int $total - Total order amount
   * @param boolean $is_paid - Has the order been paid?
   * 
   * @return string - Returns a status message depending on if an error occurred
   * or if the order was successful.
   * 
   */
  public function submitOrder($user_id, $address, $comments, $total, $is_paid) {

    // input validation
    if(!is_int($user_id)) {
      return "Error - user ID must be an integer.";
    }

    // check db connection
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - database connection error.";
    }

    try {

      $result = $this->db_connection->execute_query("INSERT INTO `orders` (`order_userid`, `order_address`, `order_comments`, `order_total`, `order_ispaid`, `order_status`) VALUES (?, ?, ?, ?, ?, '1');", [$user_id, $address, $comments, $total, $is_paid]);
      
      // check order submitted successfully
      if($result !== TRUE) {
        return "Error - an error occurred creating the order.";
      }
      
      // get order number from previous query
      $order_no = $this->db_connection->insert_id;

      $basket_contents = $this->getBasketContents($user_id);

      // loop through each entry in the basket
      foreach ($basket_contents as $item) {
        // add item line to database and link to order ID
        $result = $this->db_connection->execute_query("INSERT INTO `order_items` (`order_id`, `product_id`, `line_quantity`, `line_subtotal`) VALUES (?, ?, ?, ?)", [$order_no, $item["product_id"], $item["qty"], $item["subtotal"]]);
        // remove item from basket
        $this->removeFromBasket($item["entry_id"], $user_id);
      }
  
      return "Order submitted successfully.";

    }catch(Exception $e) {
      return "Error - database query error.";
    }

  }

  /**
   * Gets count of amount of basket entries a user has.
   * 
   * @param int $user_id - User ID
   * 
   * @return int|string - Returns count of items if successful, returns error if unsuccessful.
   * 
   */
  public function getBasketCount($user_id) {

    // input validation - check if supplied user ID is an integer
    if(!is_int($user_id)) {
      return "Error - User ID must be an integer.";
    }

    // check connection to database
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - Database connection error.";
    }

    // attempt to query database for basket entries count 
    // (not including quantities, this only outputs the number of unique entries in the basket)

    try {
      $result = $this->db_connection->execute_query("SELECT COUNT(`basket_userid`) FROM `basket_entries` WHERE `basket_userid` = ?;", [$user_id]);

      // check if basket is empty, if so, return a count of 0
      if($result->num_rows <= 0) {
        return 0;
      }

      // loop through data returned from db
      while ($row = $result->fetch_assoc() ) {
        // return count of basket items
        return $row["COUNT(`basket_userid`)"];
      }

      // if this is returned, something has gone very wrong somewhere...
      return "Error - database query error.";

    } catch(Exception $e) {
      // something went wrong when executing the database query
      return "Error - database query error.";
    }

  }

  /** Returns monetary total of all items in basket
   * 
   * @param $user_id User ID
   * 
   * @return float|string Returns error message or floating point number of 
   * monetary total of all items in basket.
   * 
   */
  public function getBasketTotal($user_id) {

    // input validation - check if supplied user ID is an integer
    if(!is_int($user_id)) {
      return "Error - User ID must be an integer.";
    }

    // check connection to database
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - Database connection error.";
    }

    // atempt to obtain sum of subtotals in user's basket
    try {
      $result = $this->db_connection->execute_query("SELECT SUM(`entry_subtotal`) FROM `basket_entries` WHERE `basket_userid` = ?;", [$user_id]);

      // if user's basket is empty, return a total of £0.00
      if($result->num_rows <= 0) {
        return 0.00; 
      }

      // loop through returned rows from DB
      while ($row = $result->fetch_assoc() ) {
        // return sum of all basket entry subtotals, giving the total of the basket contents
        return  $row["SUM(`entry_subtotal`)"];
      }

    } catch(Exception $e) {
      return "Error - database query error.";
    }

    return "Error - something went wrong";

  }

  /** Returns the content's of a user's basket
   * @param $user_id - User ID 
   * @return array|string Returns array of user's basket contents, or error message.
   */
  public function getBasketContents($user_id) {

    // validate user ID supplied is an integer
    if(!is_int($user_id)) {
      return "Error - User ID is not an integer.";
    }

    // check database connection
    if($this->createDatabaseConnection() !== "OK") {
      return "Error - database connection error.";
    }

    try {
      $result = $this->db_connection->execute_query("SELECT * FROM `basket_entries` WHERE `basket_userid` = ?;", [$user_id]);

      $users_basket = array();

      if($result->num_rows <= 0) {
        // basket is empty, return blank array
        return $users_basket;
      }

      while($row = $result->fetch_assoc()) {
        // append all rows of basket entries to array to return from function
        $users_basket[] = $users_basket + $row;

        // previously used to be:
        /*
          basket_entry = array();

          $basket_entry["entry_id"] = $row["basket_entry_id"];
          $basket_entry["product_id"] = $row["basket_productid"];
          $basket_entry["qty"] = $row["entry_quanitity"];
          $basket_entry["subtotal"] = $row["entry_subtotal"];

          $basket[] = $basket + $basket_entry;
      
         */
      }

      // return basket once all rows added to output array
      return $users_basket;

    } catch(Exception $e) {
      return "Error - database query error.";
    }

    
  }

    
}

$db_handler = new Database();

$db_handler->getAllProducts(true);

//echo $db_handler->testDatabaseConnection();
//echo $db_handler->checkSetup();
//echo $db_handler->createUser("220148717@aston.ac.uk", "password", "Harrison");



?>