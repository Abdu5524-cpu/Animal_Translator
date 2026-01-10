# Web PHP Stack

## Local Run
1) Start PHP's built-in server:
```
php -S 0.0.0.0:8001 -t public
```
2) Open `http://localhost:8001`.

## Configuration
- DB connection: `config/db.php`.
- Demo chat link: set `DEMO_CHAT_URL` or it defaults to `http://localhost:8002/demo.html`.

## Hosting
- Set web root (document root) to `public`.
- Ensure PHP is enabled.
- Update DB credentials in `config/db.php`.
