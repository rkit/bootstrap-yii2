# Tests

## Preparation

Create `bootstrap_yii2_tests` database and run
```
composer build:test
```

## Commands

- Run all tests
  ```
  composer test
  ```

- Run unit tests
  ```
  composer test:unit
  ```

- Run functional tests
  ```
  composer test:functional
  ```

- Reconfigure modules of codeception
  ```
  composer test:reconfig
  ```

- Run tests with coverage
  ```
  composer test:coverage
  ```

- Show coverage dashboard
  ```
  composer test:stats
  ```
