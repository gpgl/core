# GPL PHP GPG Locker Core

[![Build Status][12]][11]
[![Codecov][16]][14]
[![Latest Stable Version][7]][6]
[![Total Downloads][8]][6]
[![License][9]][6]

PHP 7 core library to manage data secured with [The GNU Privacy Guard][2].

## Installation

You can use the library in your own project with [composer][5]:

    composer require gpgl/core

## Testing

Because the tests require access to your GPG keyring,
it's best to run them inside a container.

    docker run --rm -it -v "$PWD":/code gpgl/test-core

The container is built with composer to install the dependencies too.

    docker run --rm -it -v "$PWD":/code gpgl/test-core composer install

There is a [Dockerfile][17] provided for building the test environment.

    docker build -t gpgl/test-core ./tests

[2]:https://www.gnupg.org/
[4]:https://github.com/gpgl/gpgl-core/issues
[5]:https://getcomposer.org/
[6]:https://github.com/gpgl/gpgl-core/releases/latest
[7]:https://poser.pugx.org/gpgl/gpgl-core/v/stable
[8]:https://img.shields.io/github/downloads/gpgl/gpgl-core/total.svg
[9]:https://poser.pugx.org/gpgl/gpgl-core/license
[11]:https://travis-ci.org/gpgl/gpgl-core
[12]:https://travis-ci.org/gpgl/gpgl-core.svg?branch=master
[14]:https://codecov.io/gh/gpgl/gpgl-core/branch/master
[16]:https://img.shields.io/codecov/c/github/gpgl/gpgl-core/master.svg
[17]:./tests/Dockerfile
