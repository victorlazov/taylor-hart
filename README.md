# Prerequisites
The database is sqlites so you will need `php-sqlite` module in order to
run the project.

# Initialization
Clone the codebase and advance in the newly created folder. Run the command 
`composer install` to initialize the Symfony application.

## Run the development web server

`php bin/console server:run`

## Initialize the database

Configure the database in your `.env` file e.g.

`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"`

and initialize it with the command:

`php bin/console doctrine:database:create`

## Populate table schema

To generate the database schema run the command
 
`php bin/console doctrine:migrations:migrate`

# Usage

You can start from `login`, `register` or `videos` pages.

# Configuration

Check `services.yaml` for `VideoPermissionsService` arguments.