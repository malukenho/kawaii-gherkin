<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace KawaiiGherkin\Command;

use Behat\Gherkin\Parser;
use KawaiiGherkin\FeatureResolve;
use KawaiiGherkin\Formatter\Background;
use KawaiiGherkin\Formatter\FeatureDescription;
use KawaiiGherkin\Formatter\Scenario;
use KawaiiGherkin\Formatter\Step;
use KawaiiGherkin\Formatter\Tags;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Jefersson Nathan  <malukenho@phpse.net>
 * @license MIT
 */
final class FixGherkinCodeStyle extends Command
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * {@inheritDoc}
     *
     * @param Parser $parser
     */
    public function __construct($name, Parser $parser)
    {
        parent::__construct('Kawaii Gherkin');
        $this->parser = $parser;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('gherkin:fix')
            ->setDescription('Fix gherkin code style')
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'Path to find *.feature files'
            )
            ->addOption(
                'align',
                null,
                InputOption::VALUE_OPTIONAL,
                'Side to align statement (right or left). Default right',
                'left'
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $align = $input->getOption('align') === Step::ALIGN_TO_LEFT
            ? Step::ALIGN_TO_LEFT
            : Step::ALIGN_TO_RIGHT;

        $directory = $input->getArgument('directory');
        $finder    = (new FeatureResolve($directory))->__invoke();

        $output->writeln('');
        $output->writeln('Finding files on <info>' . $directory . '</info>');
        $output->writeln('');

        $tagFormatter       = new Tags();
        $featureDescription = new FeatureDescription();
        $background         = new Background($align);
        $scenario           = new Scenario($align);

        /* @var $file \Symfony\Component\Finder\SplFileInfo */
        foreach ($finder as $file) {

            $fileContent = $file->getContents();
            $feature     = $this->parser->parse($fileContent);

            $formatted = $feature->hasTags() ? $tagFormatter->format($feature->getTags()) . PHP_EOL : '';
            $formatted .= $featureDescription->format($feature) . PHP_EOL . PHP_EOL;
            $formatted .= $feature->hasBackground() ? $background->format($feature->getBackground()) . PHP_EOL : '';
            $formatted .= $feature->hasScenarios() ? $scenario->format($feature->getScenarios()) : '';

            $filePointer = $file->openFile('w');
            $filePointer->fwrite($formatted);

            $output->writeln('<info>' . $file->getRealpath() . '</info>');
        }
    }
}
