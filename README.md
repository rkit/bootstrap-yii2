Bootstrap for Yii2
========

[![Build Status](https://img.shields.io/travis/rkit/bootstrap-yii2/master.svg?style=flat-square)](https://travis-ci.org/rkit/bootstrap-yii2)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rkit/bootstrap-yii2/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/?branch=master)

## Advanced Application Template

- Users: Roles / Registration / Basic and social authorization
- Files: Upload / Crop / Gallery
- Geo: Countries / Regions / Cities
- Tags
- Settings
- Webpack for assets

Screenshots:
[User List](https://cloud.githubusercontent.com/assets/4242765/5601755/2d9aad0c-9341-11e4-8ee2-ab5e02f90314.png),
[User Form](https://cloud.githubusercontent.com/assets/4242765/5601756/2fb0cdb0-9341-11e4-8d25-6aca3bc9baf8.png)

## Installation

1. Create project

   ```
   composer global require "fxp/composer-asset-plugin:~1.0"
   composer create-project --prefer-dist --stability=dev rkit/bootstrap-yii2
   ```

2. Check requirements
   ```
   php requirements.php
   ```

3. Create a new database and local config

   ```
   php yii create-local-config/init
   ```
   > config will be created in **config/local/config.php**

4. Run
   ```
   npm install
   bower install
   npm run build
   php yii migrate --migrationPath=@yii/log/migrations/
   php yii migrate --migrationPath=@yii/rbac/migrations/
   php yii migrate/up
   php yii rbac/init
   ```

#### Superuser
username: editor  
password: fghfgh

## Development

* To enable debug mode
  ~~~~
  SetEnv APPLICATION_ENV "development"
  ~~~~

* Run webpack to build the JS/CSS bundles
  ~~~~
  npm run watch
  ~~~~

## [Tests](https://github.com/rkit/bootstrap-yii2/tree/master/tests)
## [PHP Coding Style Guide](http://www.php-fig.org/psr/psr-2)
## [JS Coding Style Guide](https://github.com/airbnb/javascript)
