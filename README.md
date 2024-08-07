# Code Challenge - Symfony 7 Rest API
This challenge requires you to create an API that will import data from a third party API and be able to display it. 

Building Symfony RESTful APIs using Symfony 7, Doctrine, PHPUnit, Command, Service etc. 

## Features

  - Import customers from a 3rd party data provider and save to the database.
  - Display a list of customers from the database.
  - Select and display details of a single customer from the database.

## Requirements


* PHP: ">=8.2"

* MySQL: Ver 15.1 Distrib 10.4.32-MariaDB

        mysql user: root
        mysql passowrd: no password


## Build 

Clone github repository:

In your ```.env``` file, you need to configure ```DATABASE_URL```.

Install dependencies with composer:
```
composer install
```

Create the database:
```
php bin/console doctrine:database:create
```

Run migrations:
```
php bin/console doctrine:migrations:migrate
```

Run Customer Import Command
```
php bin/console app:import-customers
```


Run the application

```
symfony server:start
```

Have a check on a browser.
```
http://localhost:8000
```


## REST APIs
Create 2 RESTful API endpoints with the following route definition:
- GET /customers 

    Retrieve the list of all customers from the database. The response should contain:

        fullname
        email
        country

- GET /customers/{customerId}

    Retrieve more details of a single customer. The response should contain:

        fullname
        email
        username
        gender
        country
        city
        phone


## Test

Create test database for unit test and application test.
```
php bin/console --env=test doctrine:database:create
```

Run Test cases
```
php bin/phpunit
```
