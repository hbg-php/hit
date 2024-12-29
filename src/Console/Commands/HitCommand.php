<?php

declare(strict_types=1);

namespace Hit\Console\Commands;

use Composer\Autoload\ClassLoader;
use Hit\Kernel;
use Hit\ValueObjects\Issue;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\renderUsing;

/**
 * @codeCoverageIgnore
 *
 * @internal
 */
#[AsCommand(name: 'default')]
class HitCommand extends Command
{
    private const int NO_LINE = 0;

    protected function configure()
    {
        $this->setDescription('Checks for misspellings in the given directory.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $kernel = Kernel::default();

        $issues = $kernel->handle([
            'directory' => $directory = self::inferProjectPath(),
        ]);

        $output->writeln('');

        if ($issues === []) {
            renderUsing($output);
            render(<<<'HTML'
                <div class="mx-2 mb-1">
                    <div class="space-x-1">
                        <span class="bg-green text-white px-1 font-bold">PASS</span>
                        <span>No misspellings found in your project.</span>
                    </div>
                </div>
            HTML);

            return Command::SUCCESS;
        }

        foreach ($issues as $issue) {
            $this->renderIssue($output, $issue, $directory);
        }

        return Command::FAILURE;
    }

    protected function renderIssue(OutputInterface $output, Issue $issue, string $currentDirectory): void
    {
        renderUsing($output);

        $file = str_replace($currentDirectory, '', $issue->file);
        $lineInfo = ($issue->line !== self::NO_LINE) ? " on line <strong>{$issue->line}</strong>" : '';
        $suggestions = implode(', ', $issue->misspelling->suggestions);

        render(<<<HTML
            <div class="mx-2 mb-1">
                <div class="space-x-1">
                    <span class="bg-red text-white px-1 font-bold">ISSUE</span>
                    <span>Misspelling in <strong><a href="{$issue->file}">{$file}</a></strong>{$lineInfo}: '<strong>{$issue->misspelling->word}</strong>'</span>
                </div>

                <div class="space-x-1 text-gray-700">
                    <span>Did you mean:</span>
                    <span class="font-bold">{$suggestions}</span>
                </div>
            </div>
        HTML);
    }

    protected static function inferProjectPath(): string
    {
        $basePath = dirname(array_keys(ClassLoader::getRegisteredLoaders())[0]);

        return match (true) {
            isset($_ENV['APP_BASE_PATH']) => $_ENV['APP_BASE_PATH'],
            default => match (true) {
                is_dir($basePath.'/src') => ($basePath.'/src'),
                is_dir($basePath.'/app') => ($basePath.'/app'),
                default => $basePath,
            },
        };
    }
}