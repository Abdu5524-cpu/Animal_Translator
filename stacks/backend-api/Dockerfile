FROM php:8.3-cli
RUN docker-php-ext-install mysqli
WORKDIR /app
COPY config ./config
COPY public ./public
COPY bin ./bin
COPY schema.sql ./
# Render sets PORT. Several workers so one slow request doesn't block the rest.
ENV PORT=10000 PHP_CLI_SERVER_WORKERS=4
CMD php bin/migrate.php && exec php -S 0.0.0.0:${PORT} -t public
