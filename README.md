# SpaceTech
SpaceTech eCommerce Website (Team Project - Group 35)

## Database Handling
The database is handled using functions to carry out specific operations, rather than allowing user requests & other PHP files to directly access the database.
The following functions are used internally by `database-handler.php` to create & destroy database connections:
`createDatabaseConnection()` & `destroyDatabaseConnection()`
