# #!/bin/bash

# # Wait for MySQL to be ready
# echo "⏳ Waiting for MySQL..."
# until mysql -h db -u root -proot -e "SELECT 1;" &> /dev/null; do
#   sleep 1
# done

# echo "✅ MySQL is up - Running DDL..."
# mysql -h db -u root -proot zealphp < /app/DDL/taskddl.sql

# echo "🚀 Running Composer..."
# composer install

# echo "▶ Starting App..."
# exec php app.php
