
<p align="center">
    <img src="https://raw.githubusercontent.com/hbg-php/hit/main/docs/logo-hit.png" alt="Hit example" height="300">
    <p align="center">
        <a href="https://github.com/hbg-php/hit/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/hbg-php/hit/test.yml"></a>
        <a href="https://packagist.org/packages/hbg-php/hit"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/hbg-php/hit"></a>
        <a href="https://packagist.org/packages/hbg-php/hit"><img alt="Latest Version" src="https://img.shields.io/packagist/v/hbg-php/hit"></a>
        <a href="https://packagist.org/packages/hbg-php/hit"><img alt="License" src="https://img.shields.io/packagist/l/hbg-php/hit"></a>
    </p>
</p>

------
**Hit** is a powerful CLI tool designed to identify wording or spelling mistakes in your codebase. Built for speed, simplicity, and seamless integration.

Leveraging the robust capabilities of [github.com/tigitz/php-spellchecker](https://github.com/tigitz/php-spellchecker)

> Note: Hit is still under active development and is not yet ready for production use. Currently, only the filesystem checker is implemented, focusing exclusively on detecting spelling mistakes in file and folder names.

## Installation

> **Requires [PHP 8.3+](https://php.net/releases/)**

You can require Hit using [Composer](https://getcomposer.org) with the following command:

```bash
composer require hbg-php/hit
```

## Usage

To check your project for spelling mistakes, run:

```bash
./vendor/bin/hit
```

---

Hit is an open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
