<?php

declare(strict_types=1);

namespace KawaiiGherkin\Command;

use Behat\Gherkin\Parser;
use KawaiiGherkin\Finder;
use KawaiiGherkin\Formatter\Feature;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class FixCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'fix';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Fix gherkin code style';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dic->setService(InputInterface::class, static fn (): InputInterface => $input);

        $parser    = $this->dic->getService(Parser::class);
        $finder    = $this->dic->getService(Finder::class);
        $formatter = $this->dic->getService(Feature::class);

        foreach ($finder as $file) {
            $output->writeln("\nFixing <info>{$file->getPathname()}</info>\n");

            $feature   = $parser->parse((string) file_get_contents($file->getPathname()));
            $formatted = implode('', [...$formatter->format($feature)]);

            $filePointer = $file->openFile('w');
            $filePointer->fwrite((string) $formatted);

            $output->writeln("<info>{$file->getRealPath()}</info>");
        }

        return self::SUCCESS;
    }
}
