Bootstrap for Yii2
========

#### The template contains the basic features including users, roles and more

- Users (roles, registration, basic and social authorization)
- Files (upload, crop, gallery)
- Geo (countries, regions, cities)
- Tags
- Settings

![Screenshot](https://cloud.githubusercontent.com/assets/4242765/5601747/68365426-9340-11e4-9bf8-53b348bbdb78.png)
![Screenshot](https://cloud.githubusercontent.com/assets/4242765/5601746/661b674e-9340-11e4-8096-1562a8591bcb.png)

## Installation

1. Run `composer create-project --prefer-dist --stability=dev rkit/bootstrap2`
2. Create a new database and configuration file in [config/local/config.php](https://gist.github.com/rkit/8fa95259aace1bf4120b)
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