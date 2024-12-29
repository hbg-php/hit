<?php

declare(strict_types=1);

namespace Hit;

use Composer\Autoload\ClassLoader;

final class Config
{
    /**
     * The instance of the configuration.
     */
    private static ?self $instance = null;

    /**
     * Creates a new instance of Config.
     *
     * @param  array<int, string>  $whitelistedWords
     * @param  array<int, string>  $whitelistedDirectories
     */
    public function __construct(
        public array $whitelistedWords = [],
        public array $whitelistedDirectories = [],
    ) {
        $this->whitelistedWords = array_map(fn (string $word): string => strtolower($word), $whitelistedWords);
    }

    /**
     * Fetches the instance of the configuration.
     */
    public static function instance(): self
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }

        $basePath = dirname(array_keys(ClassLoader::getRegisteredLoaders())[0]);

        $contents = (string) file_get_contents($basePath.'/hit.json');

        /** @var array{
         *     ignore?: array{
         *         words?: array<int, string>,
         *         directories?: array<int, string>
         *     }
         *  } $jsonAsArray */
        $jsonAsArray = json_decode($contents, true) ?: [];

        return self::$instance = new self(
            $jsonAsArray['ignore']['words'] ?? [],
            $jsonAsArray['ignore']['directories'] ?? [],
        );
    }
}
