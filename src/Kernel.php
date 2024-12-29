<?php

declare(strict_types=1);

namespace Hit;

use Hit\Hiters\FileSystemHiter;
use Hit\Services\InMemoryHitHunter;

final readonly class Kernel
{
    /**
     * Creates a new instance of Kernel.
     *
     * @param  array<int, Contracts\Hiter>  $hiters
     */
    public function __construct(
        private array $hiters,
    ) {
        //
    }

    /**
     * Creates the default instance of Kernel.
     */
    public static function default(): self
    {
        $config = Config::instance();
        $inMemoryHiter = InMemoryHitHunter::default();

        return new self(
            [
                new FileSystemHiter($config, $inMemoryHiter),
            ],
        );
    }

    /**
     * Handles the given parameters.
     *
     * @param  array{directory?: string}  $parameters
     * @return array<int, ValueObjects\Issue>
     */
    public function handle(array $parameters): array
    {
        $issues = [];

        foreach ($this->hiters as $hit) {
            $issues = [
                ...$issues,
                ...$hit->hit($parameters),
            ];
        }

        return $issues;
    }
}
