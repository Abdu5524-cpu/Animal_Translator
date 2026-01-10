# API PHP Stack

## Local Run
1) Start PHP's built-in server:
```
php -S 0.0.0.0:8000 -t public
```
2) Example endpoint:
```
http://localhost:8000/login_api_get.php?username=demo&password=demo
```

## Configuration
- DB connection: `config/db.php`.
- Tests use `API_BASE_URI` (defaults to `http://localhost/stacks/backend-api/public/`).

## Composer
```
php ./tools/composer/composer.phar install
```

## Hosting
- Set web root (document root) to `public`.
- Ensure PHP extensions for `mysqli` and `curl` are enabled.
- Update DB credentials in `config/db.php`.
