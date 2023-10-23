# King Boosting

### Elo gaming boosting site

[![Check & fix styling](https://github.com/CaddyDz/KingBoosting/actions/workflows/style.yml/badge.svg)](https://github.com/CaddyDz/KingBoosting/actions/workflows/style.yml)
[![Tests](https://github.com/CaddyDz/KingBoosting/actions/workflows/test.yml/badge.svg)](https://github.com/CaddyDz/KingBoosting/actions/workflows/test.yml)

# Installation instructions

## Pre-requisites

-   Node version: 14.x
-   NPM version: 6.x
-   Composer version: 2.x
-   PHP version: 8.0
-   Laravel Nova license: ~3.0
-   MySQL version: ^8.0

### Clone the repository locally

```shell
git clone https://github.com/CaddyDz/KingBoosting.git
```

### Authenticate Nova

```shell
composer config http-basic.nova.laravel.com <username> <password>
```

### Install PHP dependencies

```shell
composer install
```

### Create a .env file

```shell
cp .env.example .env
```

> Create a MySQL database locally and fill in the database credentials in the .env file
