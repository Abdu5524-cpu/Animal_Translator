# API PHP Stack

JSON API for the Animal Translator app: register, login (JWT), and purchase history.

## Configuration
All secrets come from environment variables (see `.env.example`). Nothing secret is committed.

| Variable | Purpose | Local default |
| --- | --- | --- |
| `DB_HOST`, `DB_PORT` | MySQL / MariaDB server | `127.0.0.1`, `3306` |
| `DB_USER`, `DB_PASS`, `DB_NAME` | Database login and name | `root`, empty, `Animals` |
| `JWT_SECRET` | Signs login tokens. At least 32 random characters. | none (required) |
| `CORS_ORIGIN` | Browser origin allowed to call the API | `*` |

Create the tables once with `schema.sql`.

## Local Run
```
export JWT_SECRET="$(head -c 32 /dev/urandom | base64)"
php -S 0.0.0.0:8000 -t public
```

## Endpoints
| Endpoint | Method | Auth | Body |
| --- | --- | --- | --- |
| `register_api_get.php` | POST | none | `username`, `password`, `confirm_password` |
| `login_api_get.php` | POST | none | `username`, `password` → returns `token` |
| `getPurchase_api.php` | GET | Bearer token | none |
| `makePurchase_api.php` | POST | Bearer token | `animal` |
| `updatePurchase_api.php` | POST | Bearer token | `purchase_id`, `animal` |
| `refundPurchase_api.php` | POST | Bearer token | `purchase_id` |

Bodies may be JSON or form encoded. Send the token as `Authorization: Bearer <token>`.
The file names ending in `_get` are kept for compatibility; credentials are POST only now.

## Tests
Start the server as above against a database with `schema.sql` loaded, then:
```
API_BASE_URI=http://127.0.0.1:8000/ php vendor/bin/phpunit
```

## Hosting
- Set web root (document root) to `public`.
- Ensure PHP extensions for `mysqli` are enabled.
- Set the environment variables above on the host.
