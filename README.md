# Xililo WHM API

[![Packagist Version](https://img.shields.io/packagist/v/xililo/whm-api?style=flat-square)](https://packagist.org/packages/xililo/whm-api)
[![Packagist Downloads](https://img.shields.io/packagist/dt/xililo/whm-api?style=flat-square)](https://packagist.org/packages/xililo/whm-api)
[![PHP Version](https://img.shields.io/packagist/php-v/xililo/whm-api?style=flat-square)](https://packagist.org/packages/xililo/whm-api)
[![License](https://img.shields.io/packagist/l/xililo/whm-api?style=flat-square)](https://packagist.org/packages/xililo/whm-api)

A tidy PHP wrapper for the cPanel WHM API 1.

## Overview

This package provides a lightweight, dependency-minimal client for interacting with WHM API 1 JSON endpoints.
It includes support for:

- WHM token authentication
- account management and account queries
- hosting plan creation, updates, and inspection
- login session and provider login URL helpers

## Requirements

- PHP ^8.1
- ext-curl
- ext-json

## Installation

`ash
composer require xililo/whm-api
`

## Quick Start

`php
<?php

declare(strict_types=1);

use Xililo\WhmApi\Config;
use Xililo\WhmApi\Whm;

 = new Config(
    host: 'whm.example.com',
    username: 'root',
    token: 'your-api-token',
);

 = new Whm();

 = ->accounts()->list();

foreach (->data('acct', []) as ) {
    echo ['user'] . PHP_EOL;
}
`

## Usage

### Authentication

WHM token calls use the header format documented by cPanel:

`	ext
Authorization: whm username:token
`

#### Create a WHM user session

`php
 = ->auth()->createUserSession(
    user: 'example',
    service: 'cpaneld',
);

 = ->data('url');
`

#### Fetch a provider login URL

`php
 = ->auth()->getLoginUrl(
    provider: 'cPStore',
    urlAfterLogin: 'https://your-app.example.com/return',
);

 = ->data('url');
`

### Accounts

#### List accounts

`php
 = ->accounts()->list([
    'search' => 'example',
    'searchtype' => 'domain',
    'searchmethod' => 'exact',
]);
`

#### Create an account

`php
 = ->accounts()->create(
    username: 'example',
    domain: 'example.com',
    password: 'strong-password',
    plan: 'starter',
);
`

#### Change package

`php
->accounts()->changePackage('example', 'business');
`

#### Suspend / unsuspend

`php
->accounts()->suspend('example', 'Non-payment');
->accounts()->unsuspend('example');
`

#### Change password

`php
->accounts()->setPassword('example', 'new-password');
`

### Hosting Plans

#### List plans

`php
 = ->hostingPlans()->list();
`

#### Create a plan

`php
->hostingPlans()->create([
    'name' => 'starter',
    'quota' => 10240,
    'bwlimit' => 102400,
    'maxftp' => 10,
    'maxsql' => 10,
]);
`

#### Update a plan

`php
->hostingPlans()->update('starter', [
    'MAX_EMAIL_PER_HOUR' => 200,
]);
`

#### Inspect a plan

`php
 = ->hostingPlans()->info('starter');
`

## Response Model

Every resource returns an ApiResponse instance with helpers such as:

`php
->successful();
->metadata();
->data();
->data('acct', []);
->reason();
->command();
->raw();
->toArray();
`

## Notes

- The client calls https://{host}:2087/json-api/{function} by default.
- It automatically appends api.version=1.
- The client is dependency-light and uses cURL directly.
- Extra parameters may be passed through for WHM-specific query options.

## Links

- Packagist: https://packagist.org/packages/xililo/whm-api
- cPanel WHM API docs: https://api.docs.cpanel.net/whm/introduction

## License

MIT