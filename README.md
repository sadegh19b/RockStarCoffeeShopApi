# Rock Star Coffee Shop Api

A RESTful Laravel development challenge for managing a small coffee shop.


## Getting Started

### Clone the project

Clone the repository and switch to the repo folder.

```shell
git clone https://github.com/sadegh19b/RockStarCoffeeShopApi
```

### Install and Run the project

Install the php dependencies and after that, you need to create the database to run the migrations and configure the `.env` file.

```shell
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Testing

Run the project tests by following the command below:

```shell
php artisan test
```

## Postman Collection

For run and test api's, you can use the postman application and import the `RockStar_Coffee_Shop.postman_collection.json` file in the postman. 
