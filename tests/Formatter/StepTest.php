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

namespace KawaiiGherkinTest\Formatter;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\ScenarioNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;
use KawaiiGherkin\Formatter\Step;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \KawaiiGherkin\Formatter\Step}
 *
 * @author Jefersson Nathan <malukenho@phpse.net>
 * @covers \KawaiiGherkin\Formatter\Step
 * @group Coverage
 * @license MIT
 */
final class StepTest extends TestCase
{
    /**
     * @var Step
     */
    private $formatter;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->formatter = new Step();
    }

    public function testCanFormatStepsToTheRight()
    {
        $expected = <<<EOS
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

        self::assertSame($expected, $this->formatter->format($scenario->getSteps()));
    }

    public function testCanFormatStepsToTheLeft()
    {
        $this->formatter = new Step(Step::ALIGN_TO_LEFT);

        $expected = <<<EOS
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

        self::assertSame($expected, $this->formatter->format($scenario->getSteps()));
    }

    public function testCanFormatStepsWithPyString()
    {
        $this->formatter = new Step(Step::ALIGN_TO_LEFT);

        $expected = <<<EOS
        Given I am a Java programmer
        And I am not Kawaii
        When I start to contribute to a php project:
        """
          This is a pyString
          Multi-line :D

          Meh... kawaii hu!?
        """
        Then I start to love php
        When I go to a php events
        Then I start to become a Kawaii guy

EOS;

        $tableNode  = new PyStringNode(
            [
                'This is a pyString',
                'Multi-line :D',
                '',
                'Meh... kawaii hu!?',
            ],
            1
        );
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

        self::assertSame($expected, $this->formatter->format($scenario->getSteps()));
    }
}
