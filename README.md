## Installation Steps

Please follow the steps mentioned below:

- Clone the project 
    ```
    git clone https://github.com/apvvyas/thinklinc-test.git
    ```
- Change the directory to project directory and install the project
    ```
    composer install
    ```
  If dont have the composer installed in your system please install it using this [link](https://getcomposer.org/download/)

- After installing composer type the following command to set the sail environment up 
    ```
    vendor/bin/sail build --no-cache
    ```
- After generating the build run the following commands in the given sequence.
    ```
    vendor/bin/sail up -d
    ```
    
    ```
    cp .env.example .env
    ```
    ```
    php artisan key:generate
    ```
- Inside the current shell please execute.
    ```
    vendor/bin/sail exec mysql bash
    ```
    ```
    mysql --password= --execute='create database example_app'
    exit
    ```
- 
- You can access the api at http://localhost/api
- Below are the api url's
    
    -- "/user/login"
    -- "/user/register"

    -- Routes which require auth token are as below's
    --- "/youtube/search?search={param}"
    --- "/youtube/show/{youtubeid or id} 