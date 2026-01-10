# Demo Chat Stack

## Local Run
1) Set your API key:
```
export OPENAI_API_KEY="your_key_here"
```
2) Start PHP's built-in server:
```
php -S 0.0.0.0:8002 -t public
```
3) Open `http://localhost:8002/demo.html`.

## Hosting
- Set web root (document root) to `public`.
- Provide `OPENAI_API_KEY` in the server environment.
