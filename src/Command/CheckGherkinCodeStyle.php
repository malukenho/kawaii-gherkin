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

use Behat\Gherkin\Exception\ParserException;
use Behat\Gherkin\Parser;
use KawaiiGherkin\FeatureResolve;
use KawaiiGherkin\Formatter\Background;
use KawaiiGherkin\Formatter\FeatureDescription;
use KawaiiGherkin\Formatter\Scenario;
use KawaiiGherkin\Formatter\Step;
use KawaiiGherkin\Formatter\Tags;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Jefersson Nathan  <malukenho@phpse.net>
 * @license MIT
 */
final class CheckGherkinCodeStyle extends Command
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
    public function __construct($name, $parser)
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
            ->setName('gherkin:check')
            ->setDescription('Find wrong gherkin code styled')
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
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws ParserException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $align = $input->getOption('align') === Step::ALIGN_TO_LEFT
            ? Step::ALIGN_TO_LEFT
            : Step::ALIGN_TO_RIGHT;

        $directory = $input->getArgument('directory');
        $finder    = (new FeatureResolve($directory))->__invoke();

        $output->writeln("\nFinding files on <info>" . $directory . "</info>\n");

        $tagFormatter       = new Tags();
        $featureDescription = new FeatureDescription();
        $background         = new Background($align);
        $scenario           = new Scenario($align);

        /* @var $file \Symfony\Component\Finder\SplFileInfo */
        foreach ($finder as $file) {

            $fileContent            = $file->getContents();
            $contentWithoutComments = $this->removeComments($fileContent);
            $feature                = $this->parser->parse($fileContent);

            $formatted = $feature->hasTags() ? $tagFormatter->format($feature->getTags()) . "\n" : '';
            $formatted .= $featureDescription->format($feature) . "\n\n";
            $formatted .= $feature->hasBackground() ? $background->format($feature->getBackground()) . "\n" : '';
            $formatted .= $feature->hasScenarios() ? $scenario->format($feature->getScenarios()) : '';

            if ($formatted !== $contentWithoutComments) {

                if (! defined('FAILED')) {
                    define('FAILED', true);
                }

                $diff = new Differ("--- Original\n+++ Expected\n", false);

                $output->writeln('<error>Wrong style: ' . $file->getRealPath() . '</error>');
                $output->writeln($diff->diff($contentWithoutComments, $formatted));
            }
        }

        if (defined('FAILED')) {
            return 1;
        }

        $output->writeln('<bg=green;fg=white>     Everything is OK!     </>');
    }

    /**
     * @param string $fileContent
     *
     * @return string
     */
    private function removeComments($fileContent)
    {
        return rtrim(
            implode(
                array_filter(
                    array_map(
                        function ($line) {
                            if (0 === mb_strpos(ltrim($line), '#')) {
                                return '';
                            }

                            return rtrim($line) . "\n";
                        },
                        explode("\n", $fileContent)
                    )
                )
            )
        ) . "\n";
    }
}
