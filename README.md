# Deezer API PHP

[![Packagist](https://img.shields.io/packagist/v/pouler/deezer-api.svg)](https://packagist.org/packages/pouler/deezer-api)
[![Build Status](https://travis-ci.org/PouleR/deezer-api.svg?branch=master)](https://travis-ci.org/PouleR/deezer-api)

This is a PHP wrapper for the [Deezer API](https://developers.deezer.com/api/).

## Requirements
* PHP 7.2 or later.

## Installation
Install it using [Composer](https://getcomposer.org/):

```sh
composer require pouler/deezer-api
```

## Usage
Before using the Deezer API, you'll need to create an app at [Deezer's developer site](https://developers.deezer.com/api/).
After you've obtained an access token you can retrieve information from a user.

```php
<?php

require 'vendor/autoload.php';

$curl = new \Symfony\Component\HttpClient\CurlHttpClient();
$client = new PouleR\DeezerAPI\DeezerAPIClient($curl);
$client->setAccessToken('');

$api = new PouleR\DeezerAPI\DeezerAPI($client);

print_r($api->getUserInformation());
```
