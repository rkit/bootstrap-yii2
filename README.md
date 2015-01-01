Bootstrap2
========

## Installation

* composer create-project --prefer-dist --stability=dev rkit/bootstrap2*
* Create a configuration file — [config/local/config.php](https://gist.github.com/rkit/8fa95259aace1bf4120b)
* Run **npm install**
* Run **gulp**
* Run **php yii migrate --migrationPath=@yii/log/migrations/**
* Run **php yii migrate --migrationPath=@yii/rbac/migrations/**
* Run **php yii migrate/up**
* Run **php yii rbac/init**

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