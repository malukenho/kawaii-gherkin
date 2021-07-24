<?php

declare(strict_types=1);

namespace KawaiiGherkin;

use Symfony\Component\Finder\Finder as SymfonyFinder;

final class Finder extends SymfonyFinder
{
    public function __construct(string ...$sources)
    {
        parent::__construct();

        $this
            ->files()
            ->name('*.feature')
        ;

        foreach ($sources as $source) {
            if (is_dir($source)) {
                $this->in($source);
            } elseif (is_file($source)) {
                $this->append([$source]);
            }
        }
    }
}
