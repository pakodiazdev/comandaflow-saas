#!/bin/bash

echo "Setting up ComandaFlow hosts for multi-tenancy..."
echo

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "ERROR: This script must be run as root (use sudo)"
    echo "Usage: sudo ./setup-hosts.sh"
    exit 1
fi

echo "Running as root - OK"
echo
echo "Adding ComandaFlow domains to hosts file..."

# Backup current hosts file
cp /etc/hosts /etc/hosts.backup.$(date +%Y%m%d)

# Add ComandaFlow domains
cat >> /etc/hosts << EOF

# ComandaFlow Multi-Tenancy Setup
127.0.0.1 comandaflow.local
127.0.0.1 sushigo.comandaflow.local
127.0.0.1 realburger.comandaflow.local
127.0.0.1 danielswinds.comandaflow.local
127.0.0.1 pgadmin.comandaflow.local
EOF

echo
echo "Hosts file updated successfully!"
echo
echo "You can now access:"
echo "- Central Admin: http://comandaflow.local"
echo "- SushiGo Tenant: http://sushigo.comandaflow.local"
echo "- RealBurger Tenant: http://realburger.comandaflow.local"
echo "- DanielsWinds Tenant: http://danielswinds.comandaflow.local"
echo
echo "To remove these entries later, run: sudo ./remove-hosts.sh"
echo
