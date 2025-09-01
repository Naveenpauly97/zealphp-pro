#!bin/bash

sudo bash install-debug-deps.sh

sudo apt update

sudo apt install -y php8.3-dev php8.3-cli php8.3-common php8.3-mbstring php8.3-xml php8.3-curl php8.3-mysqli php8.3-xdebug openssl libssl-dev curl libcurl4-openssl-dev libpcre3-dev build-essential

sudo bash configure-xdebug.sh

sudo bash install-vscode-extensions.sh

php -m | grep xdebug

 php -i | grep xdebug

 