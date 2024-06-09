# Laravel Application with Docker

This repository contains a Laravel application set up to run within Docker containers. The configuration includes PHP 8.2.1, Nginx, and MySQL.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Getting Started

### 1. Clone the Repository

```sh
git clone https://github.com/danielbadry/crud-test-laravel.git
cd crud-test-laravel
```

### 2. Build and Start the Docker Containers
```sh
docker-compose up -d --build
```

### 3. Set Up Environment Variables: Make sure the database configuration in the .env file matches the settings in the docker-compose.yml file
```sh
cp .env.example .env
```

### 4. Generate Application Key
```sh
docker-compose exec app php artisan key:generate
```

### 5. Run Migrations
```sh
docker-compose exec app php artisan migrate
```

### 6.Accessing the Application
After starting the Docker containers, you can access the Laravel application in your web browser at:
http://localhost:8000


### Common Issues
 - Missing Vendor Directory: Ensure that Composer dependencies are installed by running docker-compose exec app composer install.
 - Permissions Issues: Set the correct permissions for the storage and cache directories:
	```sh
	sudo chmod -R 777 storage bootstrap/cache
	```
 - Database Issue:
	Check Database Configuration in .env File:
	```
	DB_CONNECTION=mysql
	DB_HOST=db
	DB_PORT=3306
	DB_DATABASE=laravel
	DB_USERNAME=laravel
	DB_PASSWORD=laravel
	``` 
	Create MySQL User with Correct Privileges:
	```sh
	docker-compose exec db mysql -u root -p
	then:
		CREATE USER 'laravel'@'%' IDENTIFIED BY 'laravel';
		GRANT ALL PRIVILEGES ON *.* TO 'laravel'@'%';
		FLUSH PRIVILEGES;
	then:
		docker-compose down
		docker-compose up -d --build
	```