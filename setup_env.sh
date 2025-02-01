#!/bin/bash

echo "Welcome to the Domo project setup!"
echo "Please provide the necessary configuration for both the API and the frontend."

# === API Configuration ===
echo -e "\n--- API Configuration ---"

read -p "Enter the API environment (default: dev): " APP_ENV
APP_ENV=${APP_ENV:-dev}

read -p "Enter the API secret (default: leave blank for none): " APP_SECRET

read -p "Enter the CORS allow origin (default: ^https?://localhost(:[0-9]+)?$): " CORS_ALLOW_ORIGIN
CORS_ALLOW_ORIGIN=${CORS_ALLOW_ORIGIN:-'^https?://localhost(:[0-9]+)?$'}

read -p "Enter the database user (default: admin): " DB_USER
DB_USER=${DB_USER:-admin}

read -sp "Enter the database password (default: fJMAC3BJLx8ksQFm): " DB_PASSWORD
DB_PASSWORD=${DB_PASSWORD:-fJMAC3BJLx8ksQFm}
echo ""

read -p "Enter the database host (default: host.docker.internal:7070): " DB_URL
DB_URL=${DB_URL:-host.docker.internal:7070}

read -p "Enter the database name (default: domo): " DB_DATABASE
DB_DATABASE=${DB_DATABASE:-domo}

# === JWT Configuration ===
echo -e "\n--- JWT Configuration ---"
read -p "Enter the JWT passphrase (default: leave blank for none): " JWT_PASSPHRASE

# === Generate JWT keys ===
echo -e "\nGenerating JWT keys..."

JWT_KEY_DIR="./api/config/jwt"
mkdir -p $JWT_KEY_DIR

openssl genrsa -aes256 -passout pass:$JWT_PASSPHRASE -out $JWT_KEY_DIR/private.pem 4096

openssl rsa -in $JWT_KEY_DIR/private.pem -passin pass:$JWT_PASSPHRASE -pubout -out $JWT_KEY_DIR/public.pem

echo "✅ JWT keys generated successfully in $JWT_KEY_DIR"

# === Frontend Configuration ===
echo -e "\n--- Frontend Configuration ---"

read -p "Enter the API URL for the frontend (default: http://localhost:6969): " VITE_API_URL
VITE_API_URL=${VITE_API_URL:-http://localhost:6969}

# === Create API .env File ===
cat <<EOT > ./api/.env
###> symfony/framework-bundle ###
APP_ENV=$APP_ENV
APP_SECRET=$APP_SECRET
###< symfony/framework-bundle ###

###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN=$CORS_ALLOW_ORIGIN
###< nelmio/cors-bundle ###

###> database credentials ###
DB_USER=$DB_USER
DB_PASSWORD=$DB_PASSWORD
DB_URL=$DB_URL
DB_DATABASE=$DB_DATABASE
###< database credentials ###

###> doctrine/doctrine-bundle ###
DATABASE_URL="postgresql://$DB_USER:$DB_PASSWORD@$DB_URL/$DB_DATABASE?serverVersion=16&charset=utf8"
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=$JWT_PASSPHRASE
###< lexik/jwt-authentication-bundle ###
EOT

echo "✅ API environment configuration written to ./api/.env successfully!"

# === Create Frontend .env.production File ===
cat <<EOT > ./front/.env.production
VITE_API_URL=$VITE_API_URL
EOT

echo "✅ Frontend configuration written to ./front/.env.production successfully!"

echo "Setup completed! 🚀"
