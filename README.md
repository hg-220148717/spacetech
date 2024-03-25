# SpaceTech
SpaceTech eCommerce Website (Team Project - Group 35)

## Group Members
The group is made up of the following members:

| GitHub Username | Name |
| --------------- | ---- |
| @hg-220148717   | Harrison Green |
| @IsmailDahir    | Ismail Dahir |
| @jasica-sethi17 | Jasica Sethi |
| @KiranRai1      | Gurkiran Rai |
| @MujtabahAhmed  | Mujtabah Ahmed |
| @preet-kaur02   | Jaskiranpreet Jassal | 
| @shawn-j123     | Shawn Johnson | 
| @sufiyanshaikh07 | Sufiyan Shaikh |

## INSTALL

To install, copy the files onto your desired web server. Edit `PHP/database-handler.php` with your database credentials which have full access to the databse. The database should be blank. Attempt to access the directory where the app is installed, and you should be able to create an account and setup with your desired products & categories.


## Database Handling
The database is handled using functions to carry out specific operations, rather than allowing user requests & other PHP files to directly access the database.

### Internal Functions 

The following functions are used internally by `database-handler.php` to create & destroy database connections:

| Function    | Description |
| ----------- | ----------- |
| `createDatabaseConnection()`    | intialises the connection to the database       |
| `destroyDatabaseConnection()`   | destroys the connection to the database after database use        |
| `checkSetup()` | check if the database has been initialsed, and run setup function if not setup |
| `runSetup()` | intialises the database for a new installation |
| `createPasswordHash($password)` | takes a parameter of `$password` and outputs a hash |
| `checkPassword($input, $hash)` | checks a user's input against the stored hash, returns true if password is valid |

These functions cannot be directly accessed, but allow for use by internal functions to connect and interface with the database.

### Testing Connectivity

The database connection can be tested by calling `testDatabaseConnection()`, which will return "OK" if the connection was successful or an error message if unsuccessful. This function is a good example for how to handle database connections in this class.

### User Creation & Authentication

Creating a new user can be done by calling `createUser($email, $password, $hash)`.

Checking a user's inputted username & password can be done by calling `checkCredentials($email, $password)`.

### Product & Category Retrieval

Retrieving products from the database can be done by calling the function `getAllProducts($includeDisabledProducts)`. The variable `$includeDisabledProducts` can be set to true to return all products, even if they are disabled, or false, to only include enabled products.

Retrieving categories from the database can be done by calling the function `getAllCategories($includeDisabledCategories)`. The variable `$includeDisabledCategories` can be set to true to return all categories, even if they are disabled, or false, to only include enabled categories.

A product can also be retrieved from the database using the `getProductByID($id)` function, which takes an integer as a parameter and returns product details based on the given product ID.

A list of products can also be retrieved from the database using the `getProductsByCategoryID($category_id)` function, which takes an integer as a parameter and returns a list of product details based on the given category ID.

A list of products can also be retrieved from the database using the `getProductsByName($inputted_name)` function, which takes a string as a parameter and returns a list of matching products and their details based on the given search query.

### Product & Category Creation

A category can be created by calling the function `createCategory($name, $is_disabled, $image_path)`, which takes the name for the category, an option to disable the category, and a path to a locally stored image.

A product can be created by calling the function `createProduct($name, $category_id, $desc, $price, $stockcount, $is_disabled)`, which takes the desired name, category ID (must be valid), description, price, current amount of stock, and option to disable.