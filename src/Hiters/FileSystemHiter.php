<?php

declare(strict_types=1);

namespace Hit\Hiters;

use Hit\Config;
use Hit\Contracts\Hiter;
use Hit\Contracts\Hithunter;
use Hit\ValueObjects\Issue;
use Hit\ValueObjects\Misspelling;
use Symfony\Component\Finder\Finder;

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

            $issues = [
                ...$issues,
                ...array_map(
                    fn (Misspelling $misspelling): Issue => new Issue(
                        $misspelling,
                        $filesOrDirectory->getRealPath(),
                        0,
                    ),
                    $this->hithunter->hit($name),
                ),
            ];
        }

        usort($issues, fn (Issue $a, Issue $b): int => $a->file <=> $b->file);

        return array_values($issues);
    }
}
