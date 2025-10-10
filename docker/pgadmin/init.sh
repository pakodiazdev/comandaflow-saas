#!/bin/sh

# Set proper permissions for pgpass file
if [ -f /var/lib/pgadmin/pgpass ]; then
    chmod 600 /var/lib/pgadmin/pgpass
    chown pgadmin:pgadmin /var/lib/pgadmin/pgpass
fi

# Start pgAdmin
exec /entrypoint.sh