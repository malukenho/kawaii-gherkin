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

use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\OutlineNode;
use KawaiiGherkin\Formatter\Example;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see \KawaiiGherkin\Formatter\Example}
 *
 * @author Jefersson Nathan <malukenho@phpse.net>
 * @covers \KawaiiGherkin\Formatter\Example
 * @group Coverage
 * @license MIT
 */
final class ExampleTest extends TestCase
{
    /**
     * @var Example
     */
    private $formatter;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->formatter = new Example();
    }

    public function testCanGenerateExamplesTableProperly(): void
    {
        $expected = <<<EOS
        Examples:
          | url                  |
          | github.com/malukenho |

EOS;

        $outlineNode = new OutlineNode(
            '',
            [],
            [],
            new ExampleTableNode([['url'], ['github.com/malukenho']], 'Examples'),
            'Scenario Outline',
            1
        );

        self::assertSame($expected, $this->formatter->format($outlineNode));
    }

    public function testShouldReturnVoidIfThereIsNoExample()
    {
        $outlineNode = new OutlineNode(
            '',
            [],
            [],
            new ExampleTableNode([], 'Examples'),
            'Scenario Outline',
            1
        );

        self::assertNull($this->formatter->format($outlineNode));
    }
}
