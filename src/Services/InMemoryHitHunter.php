<?php

declare(strict_types=1);

namespace Hit\Services;

use Hit\Config;
use Hit\Contracts\Hithunter;
use PhpSpellcheck\Spellchecker\Aspell;

final readonly class InMemoryHitHunter implements Hithunter
{
    public function __construct(
        private Config $config,
        private Aspell $aspell
    )
    {
        //
    }

    public static function default(): self
    {
        return new self(
            Config::instance(),
            Aspell::create()
        );
    }

    public function hit(string $text): array
    {
        return [];
    }
}
