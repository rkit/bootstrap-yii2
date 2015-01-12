Bootstrap for Yii2
========

#### The template contains the basic features including users, roles and more

- Users (roles, registration, basic and social authorization)
- Files (upload, crop, gallery)
- Geo (countries, regions, cities)
- Tags
- Settings

Screenshots:
[User List](https://cloud.githubusercontent.com/assets/4242765/5601755/2d9aad0c-9341-11e4-8ee2-ab5e02f90314.png),
[User Form](https://cloud.githubusercontent.com/assets/4242765/5601756/2fb0cdb0-9341-11e4-8d25-6aca3bc9baf8.png)

## Installation

1. Create project 

   ```
   composer create-project --prefer-dist --stability=dev rkit/bootstrap2
   ```

2. Create a new database and configuration file in [config/local/config.php](https://gist.github.com/rkit/8fa95259aace1bf4120b)

3. Run
   ```
   npm install
   gulp
   php yii migrate --migrationPath=@yii/log/migrations/
   php yii migrate --migrationPath=@yii/rbac/migrations/
   php yii migrate/up
   php yii rbac/init
   ```

## Debug Mode

~~~~
SetEnv APPLICATION_ENV "development"
~~~~

~~~~
gulp --debug
~~~~

## BrowserSync
~~~~
gulp --sync
~~~~

## Standards

### [PHP Coding Style: PSR-2](http://www.php-fig.org/psr/psr-2)