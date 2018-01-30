# Bootstrap for Yii2

[![Build Status](https://travis-ci.org/rkit/bootstrap-yii2.svg?branch=master)](https://travis-ci.org/rkit/bootstrap-yii2)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/?branch=master)

## Features

- Users, Roles, Registration, Basic and social authorization
- [Settings](https://github.com/rkit/settings-yii2)
- [File Manager](https://github.com/rkit/filemanager-yii2)

## Soft

- PHP 7.1
- Node 8 + NPM 5
- Composer

## Installation

> For to setup development environment, you could use [Docker](./README.md#docker-for-development-environment)

1. Cloning a repository
   ```
   git clone https://github.com/rkit/bootstrap-yii2.git
   cd bootstrap-yii2
   ```

2. Copy [.env.dist](./.env.dist) to `.env` and specify settings

3. Creating a project
   ```sh
   composer create-project
   ```

Access to the Control Panel
```
email: editor@example.com  
password: fghfgh
```

## Configuration

### Server

For enable **debug mode**, add to nginx config:

```nginx
fastcgi_param APPLICATION_ENV development;
```

## Tests

[See docs](/tests/#tests)

## Coding Standard

- PHP Code Sniffer ([phpcs.xml](./phpcs.xml))
- ESLint ([.eslintrc](./.eslintrc))

## Docker for development environment

1. Install [Docker](https://www.docker.com/)

2. Cloning a repository
   ```
   git clone https://github.com/rkit/bootstrap-yii2.git
   cd bootstrap-yii2
   ```

3. Copy [.env.dist](./.env.dist) to `.env` and specify settings
   > MYSQL_ROOT_PASSWORD  
   > MYSQL_PASSWORD

4. Create and start containers
   ```sh
   docker-compose up -d
   ```

5. Creating a project
   ```sh
   docker-compose exec php composer create-project
   ```
