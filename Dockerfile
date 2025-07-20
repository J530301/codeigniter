FROM php:8.2-apache

# Install system dependencies and PHP extensions in one layer
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql mysqli mbstring exif pcntl bcmath gd intl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer early for better caching
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install dependencies before copying source code
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy project files
COPY . .

# Run composer scripts and final optimization
RUN composer dump-autoload --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 writable/

# Create startup script
RUN echo '#!/bin/bash\n\
export PORT=${PORT:-10000}\n\
echo "Listen $PORT" > /etc/apache2/ports.conf\n\
echo "<VirtualHost *:$PORT>" > /etc/apache2/sites-available/000-default.conf\n\
echo "    DocumentRoot /var/www/html/public" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    <Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf\n\
echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf\n\
echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    ErrorLog ${APACHE_LOG_DIR}/error.log" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    CustomLog ${APACHE_LOG_DIR}/access.log combined" >> /etc/apache2/sites-available/000-default.conf\n\
echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf\n\
exec apache2-foreground' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

EXPOSE $PORT

CMD ["/usr/local/bin/start.sh"]
