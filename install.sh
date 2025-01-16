#!/bin/bash

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "Please run as root"
    exit
fi

# Install required packages
apt update
apt install -y nginx php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-zip php8.1-gd

# Copy configuration files
cp nginx.conf /etc/nginx/sites-available/your-app
ln -s /etc/nginx/sites-available/your-app /etc/nginx/sites-enabled/
cp php-fpm.conf /etc/php/8.1/fpm/pool.d/your-app.conf

# Create required directories
mkdir -p /var/log/php
chown www-data:www-data /var/log/php

# Set up basic auth for admin area
apt install -y apache2-utils
htpasswd -c /etc/nginx/.htpasswd admin

# Set proper permissions
chown -R www-data:www-data /path/to/your/app
find /path/to/your/app -type f -exec chmod 644 {} \;
find /path/to/your/app -type d -exec chmod 755 {} \;

# Restart services
systemctl restart php8.1-fpm
systemctl restart nginx

echo "Installation complete!" 