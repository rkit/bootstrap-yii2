# Bootstrap for Yii2

[![Build Status](https://travis-ci.org/rkit/bootstrap-yii2.svg?branch=master)](https://travis-ci.org/rkit/bootstrap-yii2)
[![Code Coverage](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/?branch=master)
[![codecov.io](http://codecov.io/github/rkit/bootstrap-yii2/coverage.svg?branch=master)](http://codecov.io/github/rkit/bootstrap-yii2?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/?branch=master)

## Features

- Users, Roles, Registration, Basic and social authorization
- [Settings](https://github.com/rkit/settings-yii2)
- [File Manager](https://github.com/rkit/filemanager-yii2)

## Soft

- PHP 7.1.x
- Node.js + NPM 5.x
- Composer

> For to setup development environment, you could use [Docker](./README.md#docker-for-development-environment)

## Installation

1. Cloning a repository
   ```
   git clone https://github.com/rkit/bootstrap-yii2.git
   cd bootstrap-yii2
   ```

2. Creating a project
   ```sh
   composer create-project
   ```

3. Checking requirements
   ```
   php requirements.php
   ```

4. Creating a new database and local config
   ```
   php yii create-local-config --path=@app/config/local/main.php
   ```
   > filling in the database settings in the *config/local/main.php*

5. Build application
   ```
   composer build
   ```

Access to the Control Panel
```
username: editor  
password: fghfgh
```

## Configuring Server

- Nginx - [development config](./docker/nginx/conf.d/dev.conf)

## Debug mode

For enable debug mode, add to nginx config:

```nginx
fastcgi_param APPLICATION_ENV development;
```

## Tests

- [See docs](/tests/#tests)

## Coding Standard

- PHP Code Sniffer — [phpcs.xml](./phpcs.xml)
- ESLint — [.eslintrc](./.eslintrc)

## Docker for development environment

1. Install [Docker](https://www.docker.com/) and execute the first step of [installation](./README.md#installation)

2. Copy [.env.dist](./.env.dist) to `.env` and specify environment variables

3. Create and start containers
   ```sh
   docker-compose up -d
   ```

4. Follow the [installation](./README.md#installation) steps (skip the first step).  
   Run all commands through docker `docker-compose exec php`
