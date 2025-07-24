FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies and add PHP 8.3 PPA
RUN apt-get update && apt-get install -y \
    software-properties-common curl gnupg2 lsb-release ca-certificates && \
    add-apt-repository ppa:ondrej/php -y && \
    apt-get update

# Install PHP 8.3 and related packages
RUN apt-get install -y \
    php8.3 php8.3-cli php8.3-dev php8.3-common \
    php8.3-mysql php8.3-pgsql php8.3-curl php8.3-mbstring \
    php8.3-xml php8.3-zip php8.3-bcmath php8.3-gd php8.3-readline \
    php-pear gcc g++ make build-essential libssl-dev \
    libcurl4-openssl-dev libpcre3-dev libpq-dev unzip git wget

# Install PECL extensions
RUN yes '' | pecl install uopz && \
    yes '' | pecl install openswoole-22.1.2

# Enable extensions
RUN echo "extension=uopz.so" >> /etc/php/8.3/cli/php.ini && \
    echo "extension=openswoole.so" >> /etc/php/8.3/cli/php.ini && \
    echo "short_open_tag=On" >> /etc/php/8.3/cli/php.ini

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
# WORKDIR /app/zealphp-pro
WORKDIR /app

# Copy the app
COPY . /app

# Install dependencies
RUN composer install

# COPY entrypoint.sh /entrypoint.sh

# RUN chmod +x /entrypoint.sh

# RUN bash /app/zealphp-pro/entrypoint.sh

# ENTRYPOINT ["/entrypoint.sh"]

# Default command
# CMD ["/bin/bash"]
CMD ["php", "app.php"]


# docker build -t zealphp-app .
# docker run --rm -it -p 8081:8080 zealphp-app

# # docker run --rm -it zealphp-app

# docker-compose up --build
