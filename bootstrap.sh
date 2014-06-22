#!/bin/bash

sudo apt-get --yes update

sudo apt-get --yes install php5 php5-dev

# TODO (phuedx, 2014/06/19): Make the "Install libsodium" and "Install
# libsodium-php" steps idempotent.

# Install libsodium
sudo apt-get --yes install git-core

pushd /tmp

git clone https://github.com/jedisct1/libsodium.git
cd libsodium
./autogen.sh
./configure
make check
sudo make install
cd .. # cd libsodium

# Install libsodium-php
git clone https://github.com/jedisct1/libsodium-php.git
cd libsodium-php
phpize
./configure --with-libsodium
NO_INTERACTION=1 make test
sudo make install
sudo bash -c "echo \"extension=libsodium.so\" >> /etc/php5/mods-available/libsodium.ini"
sudo php5enmod libsodium
cd .. # cd libsodium-php

curl https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

popd
