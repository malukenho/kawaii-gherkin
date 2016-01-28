<?php

namespace KawaiiGherkin\Command;

use Behat\Gherkin\Parser;
use KawaiiGherkin\Formatter\Background;
use KawaiiGherkin\Formatter\FeatureDescription;
use KawaiiGherkin\Formatter\Scenario;
use KawaiiGherkin\Formatter\Tags;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

final class FixGherkinCodeStyle extends Command
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct($name, Parser $parser)
    {
        parent::__construct('Kawaii Gherkin');
        $this->parser = $parser;
    }

    protected function configure()
    {
        $this
            ->setName('kawaii:gherkin')
            ->setDescription('Fix gherkin code style')
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'Path to find *.feature files'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');
        $finder    = new Finder();
        $finder
            ->files()
            ->in($directory)
            ->name('*.feature');

        $output->writeln('');
        $output->writeln('Finding files on <info>' . $directory . '</info>');

        /* @var $file \Symfony\Component\Finder\SplFileInfo */
        foreach ($finder as $file) {

            $feature = $this->parser->parse(file_get_contents($file->getRealpath()));

            $tagFormatter       = new Tags();
            $featureDescription = new FeatureDescription();
            $background         = new Background();
            $scenario           = new Scenario();

            $formatted = $tagFormatter->format($feature->getTags()) . PHP_EOL;
            $formatted .= $featureDescription->format($feature->getTitle(), explode(PHP_EOL, $feature->getDescription())) . PHP_EOL . PHP_EOL;
            $formatted .= $background->format($feature->getBackground()) . PHP_EOL . PHP_EOL;
            $formatted .= $scenario->format(...$feature->getScenarios());

            $filePointer = $file->openFile('w');
            $filePointer->fwrite($formatted);

            $output->writeln('');
            $output->writeln('<info>' . $file->getRealpath() . '</info>');
        }
    }
}
