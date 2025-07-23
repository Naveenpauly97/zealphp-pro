#!/bin/bash

# sudo apt update

# sudo apt install gcc

# sudo apt install openssl libssl-dev curl libcurl4-openssl-dev libpcre3-dev build-essential php8.3-mysqlnd postgresql libpq-dev

# sudo pecl install uopz

# sudo pecl install openswoole-22.1.2

# cd /etc/php/8.3/cli/conf.d

# sudo touch 99-zealphp-openswoole.ini

# echo "extension=openswoole.so" | sudo tee -a /etc/php/8.3/cli/conf.d/99-zealphp-openswoole.ini

# echo "extension=uopz.so" | sudo tee -a /etc/php/8.3/cli/conf.d/99-zealphp-openswoole.ini

# echo "short_open_tag=on" | sudo tee -a /etc/php/8.3/cli/conf.d/99-zealphp-openswoole.ini

# php -m | grep uopz

# php -m | grep openswoole

# sudo apt install composer 


# Non interactive mode 
sudo apt update -y

sudo apt install -y gcc

sudo apt install -y openssl libssl-dev curl libcurl4-openssl-dev libpcre3-dev build-essential php8.3-mysqlnd postgresql libpq-dev

yes '' | sudo pecl install uopz

yes '' | sudo pecl install openswoole-22.1.2

sudo touch /etc/php/8.3/cli/conf.d/99-zealphp-openswoole.ini

echo "extension=openswoole.so" | sudo tee -a /etc/php/8.3/cli/conf.d/99-zealphp-openswoole.ini
echo "extension=uopz.so" | sudo tee -a /etc/php/8.3/cli/conf.d/99-zealphp-openswoole.ini
echo "short_open_tag=on" | sudo tee -a /etc/php/8.3/cli/conf.d/99-zealphp-openswoole.ini

php -m | grep uopz
php -m | grep openswoole

sudo apt install -y composer