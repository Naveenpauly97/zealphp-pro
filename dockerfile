# ---------- Base stage with common dependencies ----------
FROM ubuntu:22.04 AS base

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
    libcurl4-openssl-dev libpcre3-dev libpq-dev unzip git wget lsof inotify-tools

# Install PECL extensions
RUN yes '' | pecl install uopz && \
    yes '' | pecl install openswoole-22.1.2 && \
    yes '' | pecl install mongodb

# Enable extensions
RUN echo "extension=uopz.so" >> /etc/php/8.3/cli/php.ini && \
    echo "extension=openswoole.so" >> /etc/php/8.3/cli/php.ini && \
    echo "extension=mongodb.so" >> /etc/php/8.3/cli/php.ini && \
    echo "short_open_tag=On" >> /etc/php/8.3/cli/php.ini

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy application code
COPY . /app

# Install PHP packages
RUN composer require php-amqplib/php-amqplib mongodb/mongodb && \
    composer install

# Expose both ports (app + websocket)
EXPOSE 8080 9502

# Default command - will be overridden in docker-compose
# CMD ["php", "app.php"]

# ---------- App container stage ----------
FROM base AS app
# RUN apt-get update && apt-get install -y netcat && apt-get clean
CMD ["php", "app.php"]

# ---------- WebSocket container stage ----------
FROM base AS ws
# RUN apt-get update && apt-get install -y net-tools && apt-get clean
CMD ["php", "websocket_server.php"]


# Extra Notes :

# docker build -t zealphp-app .
# docker run --rm -it -p 8081:8080 zealphp-app

# # docker run --rm -it zealphp-app

# Build the web image
# docker build -f dockerfile.app -t zealphp-app .

# Build the db image
# docker build -f dockerfile.ws -t zealphp-app-ws .

# Run web container
# docker run -d --name app -p 8080:80 zealphp-app

# Run db container
# docker run -d --name websocket -p 9502:9502 zealphp-app-ws

# docker-compose up --build

#***********Create a log directory*********

# mkdir -p log
# # Run containers in background
# docker compose up -d --build
# # Tail logs into files
# docker logs -f zealphp-app    > log/app.log &
# docker logs -f zealphp-app-ws > log/ws.log  &
# docker logs -f zealphp-db     > log/db.log  &
# echo "Logging to log/app.log, log/ws.log, log/db.log"

# *****************************************************************************************

#***********Create a log directory********* [Windows]

# mkdir log
# New-Item log\app.log -ItemType File
# New-Item log\ws.log -ItemType File
# New-Item log\db.log -ItemType File

# Create start-logs.ps1

# # start-logs.ps1

# Write-Host "🚀 Starting containers in detached mode..."
# docker compose up -d --build
# Write-Host "📂 Writing logs to log/*.log (press Ctrl+C to stop tailing)..."
# Start-Process powershell -ArgumentList "docker logs -f zealphp-app    *> log\app.log" -NoNewWindow
# Start-Process powershell -ArgumentList "docker logs -f zealphp-app-ws *> log\ws.log"  -NoNewWindow
# Start-Process powershell -ArgumentList "docker logs -f zealphp-db     *> log\db.log"  -NoNewWindow

# run  Pwer shell

# .\start-logs.ps1

# ************************************************************************************************************

# docker-compose down --rmi all --volumes --remove-orphans;
# docker-compose build --no-cache; 
# docker-compose up --build


# Note Watcher.sh file have problem with windows line endings, so you need to run dos2unix on it.
# Fix :
# RUN apt install dos2unix -y && \
#     dos2unix /app/watcher.sh && \
#     sed -i 's/\r$//' /app/watcher.sh
# RUN chmod +x /app/watcher.sh
# docker build -t zealphp-app .