<?php

declare(strict_types=1);

namespace KawaiiGherkin\DIC;

use Behat\Gherkin\Keywords\ArrayKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use KawaiiGherkin\Command\CheckCommand;
use KawaiiGherkin\Command\FixCommand;
use KawaiiGherkin\DIC;
use KawaiiGherkin\Finder;
use KawaiiGherkin\Formatter\Background;
use KawaiiGherkin\Formatter\Examples;
use KawaiiGherkin\Formatter\Feature;
use KawaiiGherkin\Formatter\FeatureDescription;
use KawaiiGherkin\Formatter\Indentation;
use KawaiiGherkin\Formatter\Scenarios;
use KawaiiGherkin\Formatter\Steps;
use KawaiiGherkin\Formatter\Tags;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

final class Bootstrap
{
    public function build(): DIC
    {
        $dic = new DIC();

        $dic->setService(
            Application::class,
            static function (DIC $dic): Application {
                $application = new Application('Kawaii Gherkin', '1.0.3');
                $application->add($dic->getService(CheckCommand::class));
                $application->add($dic->getService(FixCommand::class));

                return $application;
            }
        );

        $dic->setService(
            FixCommand::class,
            static fn (DIC $dic): FixCommand => new FixCommand($dic)
        );

        $dic->setService(
            CheckCommand::class,
            static fn (DIC $dic): CheckCommand => new CheckCommand($dic)
        );

        $dic->setParameter(
            'autoload.directory',
            static function (DIC $dic): string {
                /**
                 * @var string
                 */
                $autoload = $dic->getParameter('autoload.file');

                return \dirname($autoload);
            }
        );

        $dic->setParameter(
            'gherkin.i18n',
            function (DIC $dic): array {
                $i18n = require $dic->getParameter('autoload.directory').'/behat/gherkin/i18n.php';

                return $i18n;
            }
        );

        $dic->setService(
            ArrayKeywords::class,
            static function (DIC $dic): ArrayKeywords {
                /**
                 * @var array<string, array<string, string>>
                 */
                $i18n = $dic->getParameter('gherkin.i18n');

                return new ArrayKeywords(
                    $i18n
                );
            }
        );

        $dic->setService(
            Lexer::class,
            static function (DIC $dic): Lexer {
                return new Lexer(
                    $dic->getService(ArrayKeywords::class)
                );
            }
        );

        $dic->setService(
            Parser::class,
            static function (DIC $dic): Parser {
                return new Parser(
                    $dic->getService(Lexer::class)
                );
            }
        );

        $dic->setParameter(
            'input.sources',
            static function (DIC $dic): array {
                $input = $dic->getService(InputInterface::class);

                $sources = $input->getArgument('sources');
                $sources = \is_array($sources) ? $sources : [$sources];

                return array_filter($sources);
            }
        );

        $dic->setParameter(
            'input.alignement',
            static function (DIC $dic): string {
                $input = $dic->getService(InputInterface::class);

                return 'left' === $input->getOption('align') ? 'left' : 'right';
            }
        );

        $dic->setParameter(
            'input.indentation',
            static function (DIC $dic): int {
                $input = $dic->getService(InputInterface::class);

                $indent = $input->getOption('indent');
                $indent = \is_array($indent) ? current($indent) : $indent;
                $indent = is_numeric($indent) ? $indent : 4;

                return (int) $indent;
            }
        );

        $dic->setService(
            Finder::class,
            static function (DIC $dic): Finder {
                /**
                 * @var string[]
                 */
                $sources = $dic->getParameter('input.sources');

                return new Finder(...$sources);
            }
        );

        $dic->setService(
            Background::class,
            static function (DIC $dic): Background {
                return new Background(
                    $dic->getService(Indentation::class),
                    $dic->getService(Steps::class),
                );
            }
        );

        $dic->setService(
            Feature::class,
            static function (DIC $dic): Feature {
                return new Feature(
                    $dic->getService(Background::class),
                    $dic->getService(Scenarios::class),
                    $dic->getService(FeatureDescription::class),
                    $dic->getService(Tags::class),
                );
            }
        );

        $dic->setService(
            Examples::class,
            static function (DIC $dic): Examples {
                return new Examples(
                    $dic->getService(Indentation::class)
                );
            }
        );

        $dic->setService(
            FeatureDescription::class,
            static function (DIC $dic): FeatureDescription {
                return new FeatureDescription(
                    $dic->getService(Indentation::class)
                );
            }
        );

        $dic->setService(
            Indentation::class,
            static function (DIC $dic): Indentation {
                /**
                 * @var int
                 */
                $indentation = $dic->getParameter('input.indentation');

                return new Indentation(
                    $indentation
                );
            }
        );

        $dic->setService(
            Scenarios::class,
            static function (DIC $dic): Scenarios {
                return new Scenarios(
                    $dic->getService(Indentation::class),
                    $dic->getService(Tags::class),
                    $dic->getService(Steps::class),
                    $dic->getService(Examples::class),
                );
            }
        );

        $dic->setService(
            Steps::class,
            static function (DIC $dic): Steps {
                /**
                 * @var string
                 */
                $alignement = $dic->getParameter('input.alignement');

                return new Steps(
                    $dic->getService(Indentation::class),
                    $alignement,
                );
            }
        );

        $dic->setService(
            Tags::class,
            static fn (DIC $dic): Tags => new Tags()
        );

        return $dic;
    }
}
