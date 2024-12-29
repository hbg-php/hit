<?php

declare(strict_types=1);

namespace Hit\Hiters;

use Hit\Config;
use Hit\Contracts\Hiter;
use Hit\Contracts\Hithunter;

/**
 * @internal
 */
readonly class FileSystemHiter implements Hiter
{
    public function __construct(
        private Config $config,
        private Hithunter $hithunter
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function hit(array $parameters): array
    {
        return [];
    }
}
