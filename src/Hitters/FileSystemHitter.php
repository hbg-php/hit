<?php

declare(strict_types=1);

namespace Hit\Hitters;

use Hit\Config;
use Hit\Contracts\Hithunter;
use Hit\Contracts\Hitter;
use Hit\ValueObjects\Issue;
use Hit\ValueObjects\Misspelling;
use Symfony\Component\Finder\Finder;

/**
 * @internal
 */
readonly class FileSystemHitter implements Hitter
{
    protected \EnchantBroker $enchantBroker;
    protected \EnchantDictionary $dictionary;

    public function __construct(private Config $config) {
        $this->enchantBroker = enchant_broker_init();
        $this->dictionary = enchant_broker_request_dict($this->enchantBroker, 'pt_BR');
    }

    /**
     * {@inheritDoc}
     */
    public function hit(array $parameters): array
    {
        $filesOrDirectories = Finder::create()
            ->notPath($this->config->whitelistedDirectories)
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->ignoreUnreadableDirs()
            ->ignoreVCSIgnored(true)
            ->in($parameters['directory']);

        $issues = [];

        foreach ($filesOrDirectories as $filesOrDirectory) {
            $name = $filesOrDirectory->getFilenameWithoutExtension();
            $name = strtolower((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $name));

            $misspelledWords = $this->checkSpelling($name);

            $issues = [
                ...$issues,
                ...array_map(
                    fn (Misspelling $misspelling): Issue => new Issue(
                        $misspelling,
                        $filesOrDirectory->getRealPath(),
                        0,
                    ),
                    $misspelledWords,
                ),
            ];
        }

        usort($issues, fn (Issue $a, Issue $b): int => $a->file <=> $b->file);

        return array_values($issues);
    }

    private function checkSpelling(string $text): array
    {
        $misspelledWords = [];
        $words = explode(' ', $text);

        foreach ($words as $word) {
            $word = trim($word);

            if (!empty($word)) {
                if (!enchant_dict_quick_check($this->dictionary, $word)) {
                    $suggestions = enchant_dict_suggest($this->dictionary, $word);

                    $misspelledWords[] = new Misspelling($word, array_slice($suggestions, 0, 4));
                }
            }
        }

        return $misspelledWords;
    }
}
