#!/bin/bash

# Script to generate SSL certificates using mkcert
# This script runs when the nginx container starts
# 
# Strategy:
# - docker/cert/ (mounted from host): Only stores rootCA.crt for persistence
# - /cert/ (internal volume): Stores generated certificates (regenerated on each start)

set -e

echo "🔐 Initializing SSL certificates..."

# Create cert directories
mkdir -p /cert
mkdir -p /app/docker/cert

# Get mkcert CA root directory
MKCERT_ROOT=$(mkcert -CAROOT)
ROOT_CA_PATH="$MKCERT_ROOT/rootCA.pem"
ROOT_CA_KEY_PATH="$MKCERT_ROOT/rootCA-key.pem"

# Create mkcert directory if it doesn't exist
mkdir -p "$MKCERT_ROOT"

# Host persistent CA path (using .crt for Windows compatibility)
HOST_CA_PATH="/app/docker/cert/rootCA.crt"
HOST_CA_KEY_PATH="/app/docker/cert/rootCA-key.pem"

# Check if persistent root CA exists in host volume
if [ -f "$HOST_CA_PATH" ] && [ -f "$HOST_CA_KEY_PATH" ]; then
    echo "✅ Found existing root CA in docker/cert/, reusing it..."
    
    # Create mkcert directory if it doesn't exist
    mkdir -p "$MKCERT_ROOT"
    
    # Copy from host volume to mkcert directory
    cp "$HOST_CA_PATH" "$ROOT_CA_PATH"
    cp "$HOST_CA_KEY_PATH" "$ROOT_CA_KEY_PATH"
    
    # Set proper permissions
    chmod 600 "$ROOT_CA_KEY_PATH"
    chmod 644 "$ROOT_CA_PATH"
    
    echo "✅ Existing root CA loaded successfully"
else
    echo "🔑 Root CA not found in docker/cert/, creating new CA..."
    
    # Create new CA
    mkcert -install
    
    # Copy new CA to host volume for persistence (using .crt extension)
    cp "$ROOT_CA_PATH" "$HOST_CA_PATH"
    cp "$ROOT_CA_KEY_PATH" "$HOST_CA_KEY_PATH"
    
    # Set proper permissions
    chmod 644 "$HOST_CA_PATH"
    chmod 600 "$HOST_CA_KEY_PATH"
    
    echo "✅ New root CA created and saved to docker/cert/"
    echo ""
    echo "╔═══════════════════════════════════════════════════════════════════╗"
    echo "║                   🔐 IMPORTANT: Install Root CA                   ║"
    echo "╚═══════════════════════════════════════════════════════════════════╝"
    echo ""
    echo "📁 Root CA location: docker/cert/rootCA.crt"
    echo ""
    echo "🪟 WINDOWS:"
    echo "   → Double-click on docker/cert/rootCA.crt"
    echo "   → Click 'Install Certificate'"
    echo "   → Select 'Local Machine' → Next"
    echo "   → Select 'Place all certificates in the following store'"
    echo "   → Browse → Select 'Trusted Root Certification Authorities'"
    echo "   → Next → Finish"
    echo ""
    echo "🍎 macOS:"
    echo "   → Double-click docker/cert/rootCA.crt"
    echo "   → Add to 'System' keychain"
    echo "   → Double-click the added certificate"
    echo "   → Expand 'Trust' → Set to 'Always Trust'"
    echo ""
    echo "🐧 Linux:"
    echo "   Ubuntu/Debian:"
    echo "   → sudo cp docker/cert/rootCA.crt /usr/local/share/ca-certificates/"
    echo "   → sudo update-ca-certificates"
    echo ""
    echo "   Fedora/RHEL/CentOS:"
    echo "   → sudo cp docker/cert/rootCA.crt /etc/pki/ca-trust/source/anchors/"
    echo "   → sudo update-ca-trust"
    echo ""
    echo "╚═══════════════════════════════════════════════════════════════════╝"
    echo ""
fi

# Always generate fresh domain certificates in /cert (temporary exchange folder)
echo "📜 Generating SSL certificates for comandaflow.local and *.comandaflow.local..."

# Generate certificates using the CA (temporary location for generation)
mkcert -key-file /cert/comandaflow.local-key.pem \
       -cert-file /cert/comandaflow.local.pem \
       comandaflow.local \
       "*.comandaflow.local" \
       "*.local" \
       localhost \
       127.0.0.1 \
       ::1

# Copy generated certificates to nginx certs directory
echo "📋 Copying certificates to /etc/nginx/certs/..."
mkdir -p /etc/nginx/certs
cp /cert/comandaflow.local.pem /etc/nginx/certs/comandaflow.local.pem
cp /cert/comandaflow.local-key.pem /etc/nginx/certs/comandaflow.local-key.pem

# Set proper permissions
chmod 644 /etc/nginx/certs/comandaflow.local.pem
chmod 600 /etc/nginx/certs/comandaflow.local-key.pem

echo "✅ SSL certificates generated and copied successfully!"
echo "📁 Certificates location: /etc/nginx/certs/"
echo "🔑 Certificate: comandaflow.local.pem"
echo "🔐 Private key: comandaflow.local-key.pem"
echo ""
echo "🎉 SSL setup complete! Your sites will be trusted after installing rootCA.crt"
echo ""
