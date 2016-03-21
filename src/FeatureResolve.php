<?php

namespace KawaiiGherkin;

use Symfony\Component\Finder\Finder;

final class FeatureResolve
{
    /**
     * @var string
     */
    private $directoryOrFile;

    /**
     * @param string $directoryOrFile
     */
    public function __construct($directoryOrFile)
    {
        $this->directoryOrFile = $directoryOrFile;
    }

    private function getDirectory()
    {
        return is_dir($this->directoryOrFile) ? $this->directoryOrFile : dirname($this->directoryOrFile);
    }

    private function getFeatureMatch()
    {
        return is_dir($this->directoryOrFile) ? '*.feature' : basename($this->directoryOrFile);
    }

    public function __invoke()
    {
        return Finder::create()
            ->files()
            ->in($this->getDirectory())
            ->name($this->getFeatureMatch());
    }
}
