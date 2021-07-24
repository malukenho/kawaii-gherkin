<?php

declare(strict_types=1);

namespace KawaiiGherkin\Command;

use Behat\Gherkin\Parser;
use KawaiiGherkin\Finder;
use KawaiiGherkin\Formatter\Feature;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'check';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Find wrong gherkin code styled';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dic->setService(InputInterface::class, static fn (): InputInterface => $input);

        $parser    = $this->dic->getService(Parser::class);
        $finder    = $this->dic->getService(Finder::class);
        $formatter = $this->dic->getService(Feature::class);
        $return    = self::SUCCESS;

        foreach ($finder as $file) {
            $fileContent = (string) file_get_contents($file->getPathname());
            $fileContent = $this->removeComments($fileContent);
            $feature     = $parser->parse($fileContent);

            $formatted = implode('', [...$formatter->format($feature)]);

            if ($formatted !== $fileContent) {
                $return = self::FAILURE;

                $outputBuilder = new UnifiedDiffOutputBuilder("--- Original\n+++ Expected\n", false);
                $diff          = new Differ($outputBuilder);

                $output->writeln("<error>Wrong style: {$file->getRealPath()}</error>");
                $output->writeln($diff->diff($fileContent, $formatted));
            }
        }

        if (self::SUCCESS === $return) {
            $output->writeln('<bg=green;fg=white>     Everything is OK!     </>');
        }

        return $return;
    }

    private function removeComments(string $gherkin): string
    {
        return rtrim(
            implode(
                '',
                array_filter(
                    array_map(
                        static function ($line) {
                            if (0 === mb_strpos(ltrim($line), '#')) {
                                return '';
                            }

                            return rtrim($line)."\n";
                        },
                        explode("\n", $gherkin)
                    )
                )
            )
        )."\n";
    }
}
