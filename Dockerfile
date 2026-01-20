FROM laravelsail/php82-composer:latest

# Add MySQL driver for PDO.
RUN docker-php-ext-install pdo_mysql
