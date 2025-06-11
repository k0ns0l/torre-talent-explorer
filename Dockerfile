FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx \ 
    wget nodejs npm mariadb-client \
    oniguruma-dev
    
RUN docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring

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

RUN mv /app/public/build/.vite/manifest.json /app/public/build/manifest.json

CMD sh /app/docker/startup.sh
