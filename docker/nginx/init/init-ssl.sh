#!/bin/bash

# Script to generate SSL certificates using mkcert
# This script runs when the nginx container starts

set -e

echo "🔐 Initializing SSL certificates..."

# Create cert directory if it doesn't exist
mkdir -p /etc/nginx/certs

# Get mkcert CA root directory
MKCERT_ROOT=$(mkcert -CAROOT)
ROOT_CA_PATH="$MKCERT_ROOT/rootCA.pem"
ROOT_CA_KEY_PATH="$MKCERT_ROOT/rootCA-key.pem"

# Check if root CA exists, if not create it
if [ ! -f "$ROOT_CA_PATH" ]; then
    echo "🔑 Root CA not found, creating new CA..."
    mkcert -install
    echo "✅ Root CA created and installed in container"
else
    echo "✅ Root CA already exists, using existing CA"
fi

# Always generate fresh certificates to avoid expiration
echo "📜 Generating fresh SSL certificates for comandaflow.local and *.comandaflow.local..."

# Generate certificates using existing CA
mkcert -key-file /etc/nginx/certs/comandaflow.local-key.pem \
       -cert-file /etc/nginx/certs/comandaflow.local.pem \
       comandaflow.local \
       "*.comandaflow.local" \
       localhost \
       127.0.0.1

# Set proper permissions
chmod 600 /etc/nginx/certs/comandaflow.local-key.pem
chmod 644 /etc/nginx/certs/comandaflow.local.pem

echo "✅ SSL certificates generated successfully!"
echo "📁 Certificates location: /etc/nginx/certs/"
echo "🔑 Certificate: comandaflow.local.pem"
echo "🔐 Private key: comandaflow.local-key.pem"

# Copy root CA to host volume for user installation (this is the important one)
if [ -d "/app/docker/cert" ]; then
    echo "📋 Copying root CA to host volume..."
    cp "$ROOT_CA_PATH" /app/docker/cert/rootCA.pem
    chmod 644 /app/docker/cert/rootCA.pem
    echo "✅ Root CA copied to /app/docker/cert/rootCA.pem"
    echo ""
    echo "💡 IMPORTANT: Install the root CA certificate on your system:"
    echo "   sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain /path/to/docker/cert/rootCA.pem"
    echo ""
    echo "   Or manually:"
    echo "   1. Open Keychain Access"
    echo "   2. File → Import Items..."
    echo "   3. Select docker/cert/rootCA.pem"
    echo "   4. Double-click the imported certificate"
    echo "   5. Expand 'Trust' section"
    echo "   6. Set 'Use this certificate' to 'Always Trust'"
    echo "   7. Close and save"
    echo ""
    echo "   After installing the root CA, all generated certificates will be trusted!"
fi
