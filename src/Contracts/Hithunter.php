<?php

declare(strict_types=1);

namespace Hit\Contracts;

use Hit\ValueObjects\Misspelling;

/**
 * @internal
 */
interface Hithunter
{
    /**
     * Hit the text for misspellings.
     *
     * @return array<int, Misspelling>
     */
    public function hit(string $text): array;
}
