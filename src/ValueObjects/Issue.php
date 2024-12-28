<?php

namespace Hit\ValueObjects;

/**
 * @internal This class is not meant to be used outside.
 */
final readonly class Issue
{
    /**
     * Issue constructor.
     */
    public function __construct(
        public Misspelling $misspelling,
        public string $file,
        public int $line,
    ) {}
}
