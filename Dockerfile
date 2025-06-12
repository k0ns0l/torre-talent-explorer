FROM php:8.2-fpm-alpine

# Default SQLite setup - lightweight
RUN apk add --no-cache \
    nginx \
    wget \
    nodejs \
    npm \
    sqlite \
    sqlite-dev \
    oniguruma-dev \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mbstring

# If using MariaDB/MySQL instead, uncomment the lines below 
# and comment out the SQLite section above:
    
# RUN apk add --no-cache \
#     nginx \
#     wget \
#     nodejs \
#     npm \
#     mariadb-client \
#     oniguruma-dev \
#     && rm -rf /var/cache/apk/*
# 
# RUN docker-php-ext-install \
#     pdo \
#     pdo_mysql \
#     mbstring

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app

COPY . /app
COPY package*.json /app/

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --no-dev

RUN chown -R www-data: /app

RUN cd /app && npm ci
RUN cd /app && npm run build

CMD sh /app/docker/startup.sh