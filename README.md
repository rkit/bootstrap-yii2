Bootstrap for Yii2
========

[![Build Status](https://img.shields.io/travis/rkit/bootstrap2/master.svg?style=flat-square)](https://travis-ci.org/rkit/bootstrap2)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rkit/bootstrap2/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/rkit/bootstrap2/?branch=master)

#### The template contains the basic features including users, roles and more

- Users (roles, registration, basic and social authorization)
- Files (upload, crop, gallery)
- Geo (countries, regions, cities)
- Tags
- Settings

Screenshots:
[User List](https://cloud.githubusercontent.com/assets/4242765/5601755/2d9aad0c-9341-11e4-8ee2-ab5e02f90314.png),
[User Form](https://cloud.githubusercontent.com/assets/4242765/5601756/2fb0cdb0-9341-11e4-8d25-6aca3bc9baf8.png)


## Check requirements

```
php requirements.php
```

## Installation

1. Create project 

   ```
   composer global require "fxp/composer-asset-plugin:~1.0"
   composer create-project --prefer-dist --stability=dev rkit/bootstrap2
   ```

2. Create a new database and local config

   ```
   php yii create-local-config/init
   ```
   > config will be created in **config/local/config.php**

3. Run
   ```
   npm install
   bower install
   webpack --config assets/webpack.config.js
   php yii migrate --migrationPath=@yii/log/migrations/
   php yii migrate --migrationPath=@yii/rbac/migrations/
   php yii migrate/up
   php yii rbac/init
   ```

**Authentication in control panel:**  
username: editor  
password: fghfgh

## Debug Mode

~~~~
SetEnv APPLICATION_ENV "development"
~~~~

~~~~
webpack --config assets/webpack.config.js --watch
~~~~

## [Tests](https://github.com/rkit/bootstrap2/tree/master/tests)
## [Coding Style Guide (PSR-2)](http://www.php-fig.org/psr/psr-2)