# Web PHP Stack

## Local Run
1) Start PHP's built-in server:
```
php -S 0.0.0.0:8001 -t public
```
2) Open `http://localhost:8001`.

## Configuration
- DB connection: `config/db.php` reads `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME` from the environment (local XAMPP defaults otherwise). Tables: `../backend-api/schema.sql`.
- Demo chat link: set `DEMO_CHAT_URL` or it defaults to `http://localhost:8002/demo.html`.

## Hosting
- Set web root (document root) to `public`.
- Ensure PHP is enabled.
- Set the DB environment variables on the host. Never put credentials in the PHP files.
