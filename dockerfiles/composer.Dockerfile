FROM composer:latest

WORKDIR /var/www/kino

ENTRYPOINT ["composer", "--ignore-platform-reqs"]