#!/bin/bash

echo "Installing recommended VSCode extensions for ZealPHP development..."

# PHP Debug extension
code --install-extension xdebug.php-debug

# PHP Intelephense (better than the default PHP extension)
code --install-extension bmewburn.vscode-intelephense-client

# JSON support
code --install-extension ms-vscode.vscode-json

# Additional helpful extensions
code --install-extension formulahendry.auto-rename-tag
code --install-extension ms-vscode.vscode-typescript-next

echo "VSCode extensions installed!"
echo "Please restart VSCode to activate all extensions."