FROM nginx:latest

# Install required packages
RUN apt-get update && apt-get install -y \
    wget \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Install mkcert
RUN wget -O /usr/local/bin/mkcert https://github.com/FiloSottile/mkcert/releases/download/v1.4.4/mkcert-v1.4.4-linux-amd64 \
    && chmod +x /usr/local/bin/mkcert

# Create directories
RUN mkdir -p /etc/nginx/certs /etc/nginx/conf.d

# Copy nginx configuration
COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/
COPY docker/nginx/init/init-ssl.sh /docker-entrypoint.d/init-ssl.sh

# Make init script executable
RUN chmod +x /docker-entrypoint.d/init-ssl.sh

# Expose ports
EXPOSE 80 443
