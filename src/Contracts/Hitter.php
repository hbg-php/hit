<?php

namespace Hit\Contracts;

use Hit\ValueObjects\Issue;

/**
 * @internal
 */
interface Hitter
{
    /**
     * Hit the code for issues.
     *
     * @param  array<string, string>  $parameters
     * @return array<int, Issue>
     */
    public function hit(array $parameters): array;
}
