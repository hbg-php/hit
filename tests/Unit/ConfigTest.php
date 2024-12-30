<?php

use Hit\Config;

it('should have a default configuration', function (): void {
    $config = Config::instance();

    expect($config->whitelistedWords)->toBe(['config', 'http'])
        ->and($config->whitelistedDirectories)->toBe([]);
});

it('should to be a singleton', function (): void {
    $configA = Config::instance();
    $configB = Config::instance();

    expect($configA)->toBe($configB);
});
