<?php

namespace Hit\ValueObjects;

/**
 * @internal This class is not meant to be used outside.
 */
final readonly class Misspelling
{
    /**
     * Misspelling constructor.
     *
     * @param  array<int, string>  $suggestions
     */
    public function __construct(
        public string $word,
        public array $suggestions
    ) {}
}
