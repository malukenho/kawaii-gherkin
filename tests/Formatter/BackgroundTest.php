<?php

namespace KawaiiGherkinTest\Formatter;

use Behat\Gherkin\Node\BackgroundNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;
use KawaiiGherkin\Formatter\Background;
use Prophecy\Argument;

final class BackgroundTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Background
     */
    private $formatter;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->formatter = new Background();
    }

    public function testCanFormatFeatureDescription()
    {
        $expected = <<<EOS
    Background: Turning people kawaii
        Given there's Japan registered as a country
          And there's normal person on database:
            |  nick       |  active       |
            |  malukenho  |  user allowed |
          And "malukenho" is a kawaii person
EOS;

        $tableNode = new TableNode([
            [' nick ', ' active '],
            [' malukenho ', ' user allowed'],
        ]);
        $background = new BackgroundNode(
            '   Turning people kawaii    ',
            [
                new StepNode('Given  ', '       there\'s Japan registered as a country   ', [], 1, 'Given'),
                new StepNode('  And', '  there\'s normal person on database: ', [$tableNode], 2, 'And'),
                new StepNode(' And ', '  "malukenho" is a kawaii person ', [], 5, 'And'),
            ],
            'Background',
            1
        );

        self::assertSame($expected, $this->formatter->format($background));
    }
}
