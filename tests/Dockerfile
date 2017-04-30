FROM ubuntu:16.04

MAINTAINER Jeff Puckett

RUN apt-get update && apt-get install -y php php-mbstring wget php-zip php-xml php-xdebug \
    && EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig) \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');") \
    && if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; \
        then                                                \
            >&2 echo 'ERROR: Invalid installer signature';  \
            rm composer-setup.php;                          \
            exit 1;                                         \
        fi                                                  \
    && php composer-setup.php --quiet \
    && mv composer.phar /usr/bin/composer \
    && apt-get purge -y wget \
    && apt-get clean \
    && rm -rf composer-setup.php /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /code

CMD ["./vendor/bin/phpunit"]
