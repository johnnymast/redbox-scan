[![Build Status](https://travis-ci.org/johnnymast/redbox-scan.svg?branch=master)](https://travis-ci.org/johnnymast/redbox-scan) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/johnnymast/redbox-scan/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/johnnymast/redbox-scan/?branch=master) 
[![Code Coverage](https://scrutinizer-ci.com/g/johnnymast/redbox-scan/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/johnnymast/redbox-scan/?branch=master)
[![GitHub stars](https://img.shields.io/badge/HHVM-Ready-green.svg)](http://hhvm.com/)
[![Twitter URL](https://img.shields.io/twitter/url/http/shields.io.svg?style=social&label=Contact author)](https://twitter.com/intent/tweet?text=@mastjohnny)

# Redbox-scan

Redbox-scan is a filesystem scanning and analyzing tool that allows you to scan your filesystem for new and modified files. The API is flexible and easy to use even for beginning developers.
If you combine Redbox-scan with Redbox-cli you could build a powerful CLI (Commandline Interface) application to secure your servers within even minutes. An other use case for the API could be a trigger for rsyncing
files to a new location.

## Examples

In the examples folder you find basic examples of how to scan your filesystem. If you want to learn howto write your own Adapter i suggest checking out the database example.

## Installation

Using [composer](https://packagist.org/packages/redbox/scan):

```bash
$ composer require redbox/scan
```

## Installation trough archive download

If you download the package from a website (for example [github.io](https://github.com/johnnymast/redbox-scan/) or [phpclasses.org](http://www.phpclasses.org/package/9573-PHP-Scan-files-for-new-or-modified-files.html) or any other) you will need composer installed on your machine.
The reason for this is that Redbox-scan comes without the require vendor directory which is required to run the package.

First of all if you don't have composer installed you can find it [here](https://getcomposer.org/) follow the instructions and don't get intimidated in fact its really really easy to install.

In the this sample i will assume you have composer installed (on any machine). Go to the package root (where composer.json is located) and execute the following command.

```bash
$ composer install  --no-dev
```

Now your almost ready to go. In your project require the redbox-scan.php (located in the package root). Assuming that Redbox-scan was installed in ./lib/redbox-scan/ your php file would look like this.

```php
<?php
require 'lib/redbox-scan/redbox-scan.php';
// more of your nice code below
```

And you are ready use Redbox-scan in your application.


## Unit Testing 

Redbox-scan comes with a large suite of tests that you can run. The packages has 2 test suites you can run its the normal phpunit default test including tests for the FTPAdapter and there is the **travis** test 
suite that excludes the FTPAdapter tests. As the **travis** test suite implies this package has automated builds on [trevis-ci.com](https://scrutinizer-ci.com/g/johnnymast/redbox-scan/) to make sure the package is stable with every commit.
Do not download any code from any branch that has a build failed status because it will not work for you. 


## Requirements

The following versions of PHP are supported by this version.

+ PHP 5.3
+ PHP 5.4
+ PHP 5.5
+ PHP 5.6
+ PHP 7
+ HHVM
+ Symfony Yaml 2.8

## Development Requirements

+ Phpunit 4.8


## Author

This package is created and maintained by [Johnny Mast](https://github.com/johnnymast). If you have any questions feel free to contact me on twitter by using [@mastjohnny](https://twitter.com/intent/tweet?text=@mastjohnny) in your tweet.


## License

Redbox-scan is released under the MIT public license.

[LICENSE](https://github.com/johnnymast/redbox-scan/blob/master/LICENSE.md)

 
## Enjoy

 Oh and if you've come down this far, you might as well [follow me](https://twitter.com/mastjohnny) on twitter.