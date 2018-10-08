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

You can start from `login`, `register` or `videos` pages. If you want to be admin, your user name
should be `admin`.

# Configuration

Check `services.yaml` for `VideoPermissionsService` arguments.

# Tests

Set up database connection for the unit tests creating `phpunit.xml` in application root based on
`phpunit.xml.dist` adding a line like this:
`<env name="DATABASE_URL" value="mysql://admin@127.0.0.1:3306/taste" />` in the `<env>` section.
 
It's best the database to be without any data.

You can run the unit tests with the command `./bin/phpunit`.