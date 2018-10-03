# Prerequisites
The database is sqlites so you will need `php-sqlite` module in order to
run the project.

# Initialization
Clone the codebase and advance in the newly created folder. Run the command 
`composer install` to initialize the Symfony application.

## Run the development web server

`php bin/console server:run`

## Initializae the database

`php bin/console doctrine:database:create`