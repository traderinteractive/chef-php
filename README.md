# Chef
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/traderinteractive/chef-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/traderinteractive/chef-php/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/traderinteractive/chef/v/stable)](https://packagist.org/packages/traderinteractive/chef)
[![Latest Unstable Version](https://poser.pugx.org/traderinteractive/chef/v/unstable)](https://packagist.org/packages/traderinteractive/chef)
[![License](https://poser.pugx.org/traderinteractive/chef/license)](https://packagist.org/packages/traderinteractive/chef)

[![Total Downloads](https://poser.pugx.org/traderinteractive/chef/downloads)](https://packagist.org/packages/traderinteractive/chef)
[![Daily Downloads](https://poser.pugx.org/traderinteractive/chef/d/daily)](https://packagist.org/packages/traderinteractive/chef)
[![Monthly Downloads](https://poser.pugx.org/traderinteractive/chef/d/monthly)](https://packagist.org/packages/traderinteractive/chef)

A PHP library that adds some functionality and support for knife-ec2.

## Requirements
The knife-ec2 integration depends on the knife-ec2 commands being available.

## Installation
This package uses [composer](https://getcomposer.org) so you can just add `traderinteractive/chef` as a dependency to your `composer.json` file.

## Usage

### Chef API Wrapper
```php
$chefEc2 = new TraderInteractive\Chef\ChefEc2(
    'bundle exec knife',
    $chefServer,
    [
        'chefClientName' => $chefClientName,
        'chefClientKey' => $chefClientKey,
        'awsAccessKeyId' => $awsId,
        'awsSecretAccessKey' => $awsSecret,
        'ec2SshUser' => $ec2SshUser,
        'ec2SshKey' => $sshKey,
    ]
);

$chefEc2->updateServers("role:{$roleToQuery}", null, [], ['--override-runlist' => "role[{$roleToExecute}]", '--no-color']);
```

## Upgrading from old versions
Version 3.0 removed the class TraderInteractive\Chef\Chef.  The interface was not compatible with PHP 7.
