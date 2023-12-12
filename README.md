## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Alternative installation is possible without local dependencies relying on [Docker](#docker).

### Clone the repository

    git clone https://github.com/hayknazaryann/notepad.git

### Switch to the repo folder

    cd notepad

### Install all the dependencies using composer

    composer install

### Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

### Generate a new application key

    php artisan key:generate

### Clear caches

    php artisan optimize:clear


### Run the database migrations (**Set the database connection in .env before migrating**)
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate --seed

### Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000




# Docker

To install with [Docker](https://www.docker.com), run following commands:

```
git clone https://github.com/hayknazaryann/notepad.git
cd notepad
cp .env.example.docker .env
docker-compose build
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan optimize:clear
docker-compose exec app php artisan migrate --seed
```

You can now access to the server at http://localhost:8000

