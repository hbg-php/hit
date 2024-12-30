<?php

declare(strict_types=1);

namespace Hit\Services;

use Hit\Config;
use Hit\Contracts\Hithunter;
use Hit\ValueObjects\Misspelling;
use PhpSpellcheck\MisspellingInterface;
use PhpSpellcheck\Spellchecker\Aspell;

final readonly class InMemoryHitHunter implements Hithunter
{
    public function __construct(
        private Config $config,
        private Aspell $aspell
    ) {
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
        $misspellings = $this->filterWhiteListedWords(iterator_to_array($this->aspell->check($text)));

        return array_map(fn (MisspellingInterface $misspelling): Misspelling => new Misspelling(
            $misspelling->getWord(),
            array_slice($misspelling->getSuggestions(), 0, 4),
        ), $misspellings);
    }

    /**
     * Filter out misspellings that are in the white list.
     *
     * @param  array<int, MisspellingInterface>  $misspellings
     * @return array<int, MisspellingInterface> $misspellings
     */
    public function filterWhiteListedWords(array $misspellings): array
    {
        return array_filter($misspellings, fn (MisspellingInterface $misspelling): bool => ! in_array(
            strtolower($misspelling->getWord()),
            $this->config->whitelistedWords
        ));
    }
}
