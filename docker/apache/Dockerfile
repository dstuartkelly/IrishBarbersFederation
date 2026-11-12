FROM httpd:2.4

# Self-signed certificate for localhost (the site is HTTPS-only, as on the server)
RUN apt-get update \
    && apt-get install -y --no-install-recommends openssl \
    && rm -rf /var/lib/apt/lists/* \
    && openssl req -x509 -nodes -newkey rsa:2048 -days 825 \
        -keyout /usr/local/apache2/conf/localhost.key \
        -out /usr/local/apache2/conf/localhost.crt \
        -subj "/CN=localhost" \
        -addext "subjectAltName=DNS:localhost,IP:127.0.0.1"

COPY httpd.conf /usr/local/apache2/conf/httpd.conf
