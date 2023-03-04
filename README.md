# DiSultano

This is a simple product module that follows a challenge requirements.

<br/>

## Product Features

This is a Laravel App that is build to work as apis only.

- **Laravel** backend.
- Mysql Database, managed by Elequent ORM.
- Passport authentication.
- RESTful Web Services.
- Login and Registration Apis.
- smtp email notification

<br />

## Install

```
$ git clone https://github.com/AysarHajaj/product-module.git
```

## Product-module backend

To run the app:

```
$ cd backend
$ cp .env.example .env
$ composer install
```

- Create Database called &nbsp; "product-module-db"

```
$ php artisan migrate
$ php artisan passport:install
$ php artisan db:seed
$ php artisan serve
```

<br />
