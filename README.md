Bootstrap for Yii2
========

#### The template contains the basic features including users, roles and more

- Users (roles, registration, basic and social authorization)
- Files (upload, crop, gallery)
- Geo (countries, regions, cities)
- Tags
- Settings

![Screenshot](https://cloud.githubusercontent.com/assets/4242765/5595177/090519a6-9296-11e4-9412-9821a98612e8.png)

## Installation

1. Run `composer create-project --prefer-dist --stability=dev rkit/bootstrap2`
2. Create a new database and adjust the *$config['components']['db']* configuration [config/local/config.php](https://gist.github.com/rkit/8fa95259aace1bf4120b) accordingly.
3. Run:

* `npm install`
* `gulp`
* `php yii migrate --migrationPath=@yii/log/migrations/`
* `php yii migrate --migrationPath=@yii/rbac/migrations/`
* `php yii migrate/up`
* `php yii rbac/init`

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