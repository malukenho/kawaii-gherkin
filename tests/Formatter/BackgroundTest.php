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

use Behat\Gherkin\Node\BackgroundNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;
use KawaiiGherkin\Formatter\Background;
use Prophecy\Argument;

/**
 * Tests for {@see \KawaiiGherkin\Formatter\Background}
 *
 * @author Jefersson Nathan <malukenho@phpse.net>
 * @covers \KawaiiGherkin\Formatter\Background
 * @group  Coverage
 * @license MIT
 */
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

        $tableNode  = new TableNode([
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
