# Chef
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/dominionenterprises/chef-php.svg?style=flat)](https://scrutinizer-ci.com/g/dominionenterprises/chef-php/)

[![Latest Stable Version](http://img.shields.io/packagist/v/dominionenterprises/chef.svg?style=flat)](https://packagist.org/packages/dominionenterprises/chef)
[![Total Downloads](http://img.shields.io/packagist/dt/dominionenterprises/chef.svg?style=flat)](https://packagist.org/packages/dominionenterprises/chef)
[![License](http://img.shields.io/packagist/l/dominionenterprises/chef.svg?style=flat)](https://packagist.org/packages/dominionenterprises/chef)

A PHP library that wraps [jenssegers/php-chef](https://github.com/jenssegers/php-chef) and adds some functionality and support for knife-ec2.

## Requirements
The knife-ec2 integration depends on the knife-ec2 commands being available.

## Installation
This package uses [composer](https://getcomposer.org) so you can just add `dominionenterprises/chef` as a dependency to your `composer.json` file.

## Usage

### Chef API Wrapper
Once you've created a chef API client using [jenssegers/php-chef], you can instantiate the wrapper and perform actions.  For example:
```php
$chef = new \DominionEnterprises\Chef\Chef($chefApi);
$chef->patchDatabag('data', 'item', ['url' => 'http://example.com']);
```
