# Bootstrap for Yii2

[![Build Status](https://travis-ci.org/rkit/bootstrap-yii2.svg?branch=master)](https://travis-ci.org/rkit/bootstrap-yii2)
[![Code Coverage](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/?branch=master)
[![codecov.io](http://codecov.io/github/rkit/bootstrap-yii2/coverage.svg?branch=master)](http://codecov.io/github/rkit/bootstrap-yii2?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/?branch=master)

## Features

- Users, Roles, Registration, Basic and social authorization
- [Settings](https://github.com/rkit/settings-yii2)
- [File Manager](https://github.com/rkit/filemanager-yii2)
- [Webpack for assets](https://webpack.github.io/)

## Installation

1. Creating a project
   ```
   composer global require "fxp/composer-asset-plugin:~1.0"
   composer create-project --prefer-dist --stability=dev rkit/bootstrap-yii2
   cd bootstrap-yii2
   ```

2. Checking requirements
   ```
   php requirements.php
   ```

3. Creating a new database and local config
   ```
   php yii create-local-config --path=@app/config/local/main.php
   ```
   > filling in the database settings in the *config/local/main.php*

4. Build application
   ```
   composer build:app
   ```

Access to the Control Panel
```
username: editor  
password: fghfgh
```

## Configuring Server

The application requires the document root to be set to the `web` directory.

## Development

### Debug mode

- Apache Configuration
  ```apache
  SetEnv APPLICATION_ENV "development"
  ```

- Nginx Configuration
  ```nginx
  fastcgi_param APPLICATION_ENV development;
  ```

### Assets

- Watch mode (debug)
  ```
  npm run watch
  ```

- Build for production
  ```
  npm run build
  ```

### Tests

- [See docs](/tests/#tests)

### Coding Standard

- PHP Code Sniffer — [phpcs.xml](./phpcs.xml)
- ESLint — [.eslintrc](./.eslintrc)
