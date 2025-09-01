#!/bin/bash

# Colors for output
RED="\e[1;31m"
GREEN="\e[1;32m"
YELLOW="\e[1;33m"
RESET="\e[0m"

echo -e "${GREEN}Installing PHP Debug Dependencies for ZealPHP${RESET}"

# Update package lists
echo -e "${YELLOW}Updating package lists...${RESET}"
sudo apt update

# Install PHP and required extensions
echo -e "${YELLOW}Installing PHP 8.3 and extensions...${RESET}"
sudo apt install -y \
    php8.3-dev \
    php8.3-cli \
    php8.3-common \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-curl \
    php8.3-mysqli \
    php8.3-xdebug \
    openssl \
    libssl-dev \
    curl \
    libcurl4-openssl-dev \
    libpcre3-dev \
    build-essential \
    postgresql \
    libpq-dev

# Configure Xdebug for debugging
echo -e "${YELLOW}Configuring Xdebug...${RESET}"
sudo tee /etc/php/8.3/cli/conf.d/20-xdebug.ini > /dev/null << 'EOF'
zend_extension=xdebug.so
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=127.0.0.1
xdebug.client_port=9003
xdebug.log=/tmp/xdebug.log
xdebug.idekey=VSCODE
EOF

# Also configure for Apache/FPM if needed
sudo cp /etc/php/8.3/cli/conf.d/20-xdebug.ini /etc/php/8.3/fpm/conf.d/ 2>/dev/null || true

echo -e "${GREEN}PHP Debug dependencies installed successfully!${RESET}"
echo -e "${YELLOW}Please restart your web server if running Apache/Nginx${RESET}"