# ZealPHP Debug Setup Guide

## Prerequisites

1. **PHP 8.3** with required extensions
2. **VSCode** with PHP debug extensions
3. **Xdebug** extension installed and configured
4. **OpenSwoole** extension (already configured in this project)

## Installation Steps

### 1. Install System Dependencies

```bash
# Run the installation script
./install-debug-deps.sh

# Or manually install:
sudo apt update
sudo apt install -y php8.3-dev php8.3-cli php8.3-common php8.3-mbstring php8.3-xml php8.3-curl php8.3-mysqli php8.3-xdebug openssl libssl-dev curl libcurl4-openssl-dev libpcre3-dev build-essential
```

### 2. Configure Xdebug

```bash
# Run the Xdebug configuration script
./configure-xdebug.sh
```

### 3. Install VSCode Extensions

```bash
# Install recommended extensions
./install-vscode-extensions.sh

# Or install manually:
code --install-extension xdebug.php-debug
code --install-extension bmewburn.vscode-intelephense-client
```

### 4. Verify Installation

```bash
# Check if Xdebug is loaded
php -m | grep xdebug

# Check Xdebug configuration
php -i | grep xdebug
```

## Debugging Workflows

### Important: Debug Persistence Issue Fix

OpenSwoole keeps the server process alive between requests, which can cause Xdebug sessions to not persist. The fixes include:

1. **Automatic Xdebug trigger** in `app.php`
2. **Session persistence** in middleware
3. **Cookie-based session** maintenance
4. **Environment variables** for consistent triggering

### 1. Debug the Main Server

1. Open VSCode in the project directory
2. Set breakpoints in your PHP files
3. Press `F5` or go to Run & Debug → "Debug ZealPHP Server"
4. The server will start and VSCode will attach the debugger

### 2. Debug API Endpoints

1. Start the server: `./start-debug.sh`
2. In VSCode, go to Run & Debug → "Listen for Xdebug"
3. Make HTTP requests to your API endpoints
4. Debugger will break at your breakpoints

### 3. Debug Individual Files

1. Open a PHP file
2. Set breakpoints
3. Press `F5` and select "Debug Current PHP File"

## Test Debug Setup

### 1. Test with CLI Script

```bash
# Run the debug test script
php debug-test.php
```

### 2. Test with Web Request

1. Start the debug server: `./start-debug.sh`
2. Visit: http://localhost:8080/debug-page
3. Set breakpoints in `public/debug-page.php`

### 3. Test API Debugging

1. Set breakpoints in `api/test.php`
2. Make request: `curl http://localhost:8080/api/test`

## Debug Configuration Files

- `.vscode/launch.json` - Debug configurations
- `.vscode/settings.json` - PHP and Intelephense settings
- `.vscode/tasks.json` - Build and run tasks
- `/etc/php/8.3/cli/conf.d/99-xdebug-zealphp.ini` - Xdebug config

## Troubleshooting

### Xdebug Not Working

1. Check if Xdebug is loaded:
   ```bash
   php -m | grep xdebug
   ```

2. Check Xdebug log:
   ```bash
   tail -f /tmp/xdebug.log
   ```

3. Verify port 9003 is not blocked:
   ```bash
   netstat -tlnp | grep 9003
   ```

### VSCode Not Connecting

1. Ensure the PHP Debug extension is installed
2. Check that the debug port (9003) matches in both Xdebug config and VSCode
3. Restart VSCode after configuration changes

### OpenSwoole Specific Issues

1. **Debug Persistence**: Use the provided fixes to ensure breakpoints work on subsequent requests
2. **Coroutines**: Xdebug may not work perfectly with coroutines - use `App::superglobals(true)` to disable them
3. **Session Cookies**: The debug setup automatically maintains XDEBUG_SESSION cookies
4. **Multiple Requests**: Each request should now trigger Xdebug properly

### Debug Persistence Solutions

If breakpoints still don't work on subsequent requests:

1. **Use the browser helper script**:
   ```javascript
   // Paste this in browser console
   document.cookie = "XDEBUG_SESSION=VSCODE; path=/";
   ```

2. **Add query parameter to URLs**:
   ```
   http://localhost:8080/your-endpoint?XDEBUG_SESSION_START=VSCODE
   ```

3. **Test persistence**:
   ```bash
   ./test-debug-persistence.sh
   ```

## Debug URLs

- Main app: http://localhost:8080/
- Debug info: http://localhost:8080/debug/xdebug
- PHP info: http://localhost:8080/debug/info
- Test page: http://localhost:8080/debug-page

## Environment Variables

Set these for debugging sessions:

```bash
export XDEBUG_MODE=debug
export XDEBUG_SESSION=VSCODE
```

## Performance Notes

- Xdebug will slow down execution significantly
- Disable Xdebug in production
- Use `xdebug.mode=off` to disable without uninstalling