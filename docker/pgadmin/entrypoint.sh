#!/bin/bash

# Ensure the pgadmin directory exists and has correct permissions
mkdir -p /var/lib/pgadmin

# Create pgpass file with environment variables in temp location first
PGPASS_CONTENT="# PostgreSQL password file - Auto-generated
# Format: hostname:port:database:username:password
postgres:5432:*:${DB_USER:-postgres}:${DB_PASS:-secret}"

# Write to temporary file then move to correct location
echo "$PGPASS_CONTENT" > /tmp/pgpass_temp

# Move to correct location and set permissions
cp /tmp/pgpass_temp /var/lib/pgadmin/pgpass
chmod 600 /var/lib/pgadmin/pgpass
rm -f /tmp/pgpass_temp

echo "pgpass file created successfully with credentials from environment variables"

# Start pgAdmin with original entrypoint
exec /entrypoint.sh