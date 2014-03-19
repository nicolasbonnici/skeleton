Skeleton
============

A simple skeleton project that implement the sociableCore framework (https://github.com/nicolasbonnici/sociableCore)

Dependancy

Curl & composer
See https://getcomposer.org/ for more infos

Memcache
To install memcache support for PHP5 on Linux you need to install those packages: memcached php5-memcache php-pear build-essential pecl
Then run 
pecl install memcache && echo "extension=memcache.so" | sudo tee /etc/php5/conf.d/memcache.ini

Note that the path for your php installation may be different on your distribution

Installation

Just clone this repo and run ./app/bin/console and choose install
