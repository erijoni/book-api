# ![Laravel Example App](logo.png)


> ### Short Description about Book Managment 


----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)
 

Clone the repository

    git clone git@github.com:erijoni/book.git


Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate



Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://127.0.0.1:8000

**TL;DR command list**

    git clone  git@github.com:erijoni/book.git
    cd book-management-api
    composer install
    cp .env.example .env
    php artisan migrate
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve



***Note*** : It's recommended to have a clean database before migrating. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh
    


----------

# Code overview



## Folders

- `app` - Contains all the Eloquent models and core logic of the application.
- `app/Http/Controllers/Api` - Contains all the API controllers. This includes the `BookController` for managing books' CRUD operations (create, read, update, delete), and other logic for interacting with book data.
  - `BookController.php` - Handles API requests related to book creation, updating, deleting, and fetching books.
- `app/Http/Resources` - Contains all the API resources. Resources transform the data before sending it in API responses.
  - `BookResource.php` - Transforms the `Book` model data into a structured response for the API. It is used to format the response for individual book records.
- `app/Services` - Contains services responsible for handling business logic and external API calls. Services help keep controllers clean and focused on handling HTTP requests.
  - `BookService.php` - Handles the business logic for fetching cover images from external sources based on book title and ISBN. It is called in the `BookController` when storing a new book.
- `app/Models` - Contains Eloquent models that represent the database tables. 
  - `Book.php` - The Eloquent model for the `books` table, representing a book record with fields like `title`, `isbn`, `author_id`, and `cover_image`.
  - `Author.php` - The Eloquent model for the `authors` table, representing an author. It is used in the `BookController` to fetch the associated author for each book.
- `config` - Contains all the application configuration files, including settings for external services, validation rules, and other application-wide configurations.
- `database/factories` - Contains model factories for generating dummy data. This is useful for testing or seeding the database with sample data.
- `database/migrations` - Contains database migration files that define the structure of the database tables.
  - `create_books_table.php` - Migration file for creating the `books` table in the database.
  - `create_authors_table.php` - Migration file for creating the `authors` table in the database.
- `routes` - Contains all the API routes for the application, specifically the `api.php` file, where routes for handling book-related API requests are defined.
  - `api.php` - Defines the routes for managing books (e.g., `POST /books` for creating a new book, `GET /books` for listing books, etc.).
  `BookController`'s endpoints are functioning correctly.


## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

----------

# Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api


 
