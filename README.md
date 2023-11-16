# SpaceTech
SpaceTech eCommerce Website (Team Project - Group 35)

## Database Handling
The database is handled using functions to carry out specific operations, rather than allowing user requests & other PHP files to directly access the database.

The following functions are used internally by `database-handler.php` to create & destroy database connections:
`createDatabaseConnection()` & `destroyDatabaseConnection()`. These functions cannot be directly accessed, but allow for the internal functions to connect to the database.

The database connection can be tested by calling `testDatabaseConnection()`, which will echo out "OK" if the connection was successful or an error message if unsuccessful. This function is a good example for how to handle database connections in this class.