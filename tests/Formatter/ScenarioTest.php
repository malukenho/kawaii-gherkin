<?php

namespace KawaiiGherkinTest\Formatter;


use Behat\Gherkin\Node\ScenarioNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;
use KawaiiGherkin\Formatter\Scenario;

class ScenarioTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Scenario
     */
    private $formatter;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->formatter = new Scenario();
    }

    public function testCanFormatFeatureDescription()
    {
        $expected = <<<EOS
    @kawaii @kawaii-bug-12
    Scenario: Not all people who program php are becoming kawaii
        Given I am a Java programmer
          And I am not Kawaii
         When I start to contribute to a php project:
            | project                  |
            | malukenho/kawaii-gherkin |
         Then I start to love php
         When I go to a php events
         Then I start to become a Kawaii guy

EOS;

        $tableNode  = new TableNode([
            ['project'],
            ['malukenho/kawaii-gherkin'],
        ]);
        $scenario = new ScenarioNode(
            ' Not all people who program php are becoming kawaii ',
            [' kawaii ', ' kawaii-bug-12 '],
            [
                new StepNode('Given', '       I am a Java programmer ', [], 1, 'Given'),
                new StepNode('And', '  I am not Kawaii ', [], 2, 'And'),
                new StepNode('When', '  I start to contribute to a php project: ', [$tableNode], 3, 'And'),
                new StepNode('Then', '  I start to love php ', [], 4, 'And'),
                new StepNode('When', ' I go to a php events ', [], 5, 'And'),
                new StepNode('Then', 'I start to become a Kawaii guy ', [], 5, 'And'),
            ],
            'Scenario',
            1
        );

        self::assertSame($expected, $this->formatter->format($scenario));
    }
}
