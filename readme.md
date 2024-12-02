# Flipflow Home Task

This project is a Laravel-based tool designed to visualize a list of products from the Carrefour category.
The tool includes two Laravel (Artisan) commands to scrape, display and save product data from a given category URL.

## Table of Contents

-   [Requirements and Guidelines](#requirements-and-guidelines)
-   [Data to Use](#data-to-use)
-   [Installation](#installation)
-   [Local usage](#local-usage)
-   [Running with Docker](#running-commands-with-docker)
-   [Relevant files](#relevant-files)

## Requirements and Guidelines

-   Set up a Laravel project with SQLite as a database.
-   Define the data model and implement it using migrations.
-   Create two Laravel commands:
    -   The first command will receive the category URL as a parameter and store the first five products from the list in the database
    -   The second command will also receive the category URL and display a JSON with the same information.

# Data to Use

Category URL: https://www.carrefour.es/supermercado/congelados/cat21449123/c

## Installation

1.  Clone the repository:

    ```sh
    git clone git@github.com:ftcunha/flipflow-home-task.git
    cd flipflow-home-task

    ```

2.  Create and configure the .env file:

    ```sh
    ZENROW_API_KEY=your_api_key
    ZENROW_API_URL=https://api.zenrows.com
    ZENROW_API_VERSION=v1
    PRODUCT_SCRAPER_SIZE_LIMIT=5

    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=base64:your_app_key
    APP_DEBUG=true
    APP_URL=http://localhost

    DB_CONNECTION=sqlite
    DB_DATABASE=/path/to/database.sqlite

    ```

3.  Install dependencies:

    ```sh
    composer install
    ```

4.  Run database migrations:
    ```sh
    php artisan migrate
    ```

## Local Usage

1. Show Product List:

    ```sh
    php artisan show-product-list "https://www.carrefour.es/supermercado/congelados/cat21449123/c"
    ```

2. Save Product List:
    ```sh
    php artisan save-product-list "https://www.carrefour.es/supermercado/congelados/cat21449123/c"
    ```

## Running Commands with Docker

1. Pull the Docker image from Docker Hub:

    ```sh
    docker pull ftcunha/flipflow-home-task
    ```

2. Ensure you have a **.env** file in your current directory with the necessary environment variables

3. Show Product List:

    ```sh
    docker run --rm --env-file .env ftcunha/flipflow-home-task php artisan show-product-list https://www.carrefour.es/supermercado/congelados/cat21449123/c
    ```

4. Save Product List:
    ```sh
    docker run --rm --env-file .env ftcunha/flipflow-home-task php artisan save-product-list https://www.carrefour.es/supermercado/congelados/cat21449123/c
    ```

## Relevant files

-   **app/Models/Product.php**: Defines the Product model.
-   **app/Services/ProductScraperService.php**: Contains the logic for scraping product data from the given URL.
-   **database/migrations/2024_11_27_171343_create_products_table.php**: Migration file to create the products table.
-   **routes/console.php**: Defines the Artisan commands for scraping, displaying and saving product data.
-   **.env**: Environment variables configuration.
