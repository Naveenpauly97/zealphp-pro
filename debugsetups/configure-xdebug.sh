#!/bin/bash

# Configure Xdebug specifically for OpenSwoole/ZealPHP
echo "Configuring Xdebug for ZealPHP (OpenSwoole)..."

# Create Xdebug configuration
sudo tee /etc/php/8.3/cli/conf.d/99-xdebug-zealphp.ini > /dev/null << 'EOF'
; Xdebug configuration for ZealPHP/OpenSwoole
zend_extension=xdebug.so

; Debug mode
xdebug.mode=debug,develop

; Connection settings
xdebug.start_with_request=yes
xdebug.client_host=127.0.0.1
xdebug.client_port=9003
xdebug.idekey=VSCODE

; Force Xdebug to trigger on every request
xdebug.trigger_value=VSCODE
xdebug.start_upon_error=yes
xdebug.force_display_errors=0
xdebug.force_error_reporting=-1

; Logging
xdebug.log=/tmp/xdebug.log
xdebug.log_level=7

; Performance settings for OpenSwoole
xdebug.max_nesting_level=512
xdebug.var_display_max_depth=10
xdebug.var_display_max_children=256
xdebug.var_display_max_data=1024

; Profiling (optional)
xdebug.profiler_enable=0
xdebug.profiler_output_dir=/tmp

; Coverage (optional)
xdebug.coverage_enable=0
EOF

echo "Xdebug configured for ZealPHP!"
echo "Log file: /tmp/xdebug.log"
echo "Debug port: 9003"