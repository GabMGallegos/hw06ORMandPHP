from php:8.2-cli

run apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql

copy --from=composer:2 /usr/bin/composer /usr/bin/composer

workdir /app

copy composer.json composer.lock ./

run composer install --no-dev --optimize-autoloader

copy . .

cmd php -S 0.0.0.0:${PORT:-10000} -t .