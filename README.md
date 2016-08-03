# Bootstrap for Yii2

[![Build Status](https://travis-ci.org/rkit/bootstrap-yii2.svg?branch=master)](https://travis-ci.org/rkit/bootstrap-yii2)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rkit/bootstrap-yii2/?branch=master)

## Features

- Users, Roles, Registration, Basic and social authorization
- Countries, Regions, Cities
- Tags
- [Settings](https://github.com/rkit/settings-yii2)
- [File Manager](https://github.com/rkit/filemanager-yii2)
- [Webpack for assets](https://webpack.github.io/)

Screenshots:
- [User List](https://cloud.githubusercontent.com/assets/4242765/5601755/2d9aad0c-9341-11e4-8ee2-ab5e02f90314.png)
- [User Form](https://cloud.githubusercontent.com/assets/4242765/5601756/2fb0cdb0-9341-11e4-8d25-6aca3bc9baf8.png)

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
   > config will be created in *config/local/config.php*

4. Run
   ```
   npm install
   npm run build
   php yii migrate --migrationPath=@yii/log/migrations/ --interactive=0
   php yii migrate --migrationPath=@yii/rbac/migrations/ --interactive=0
   php yii migrate --migrationPath=@vendor/rkit/filemanager-yii2/src/migrations/ --interactive=0
   php yii migrate --migrationPath=@vendor/rkit/settings-yii2/src/migrations/ --interactive=0
   php yii migrate/up --interactive=0
   php yii rbac/init
   ```

Access to the Control Panel
```
username: editor  
password: fghfgh
```

## Development

- Enable debug mode for Yii
  ```
  SetEnv APPLICATION_ENV "development"
  ```

- Run webpack in watch mode
  ```
  npm run watch
  ```

- Run webpack for build
  ```
  npm run build
  ```
