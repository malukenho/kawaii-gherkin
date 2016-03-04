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

namespace Command;

use Behat\Gherkin\Node\BackgroundNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Parser;
use KawaiiGherkin\Command\CheckGherkinCodeStyle;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Tests for {@see \KawaiiGherkin\Command\CheckGherkinCodeStyle}
 *
 * @author Jefersson Nathan <malukenho@phpse.net>
 * @covers \KawaiiGherkin\Command\CheckGherkinCodeStyle
 * @group Coverage
 * @license MIT
 */
final class CheckGherkinCodeStyleTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldReturnOkIfThereIsNoFilesFound()
    {
        $kernel = $this->getMock(\stdClass::class);
        /* @var \Behat\Gherkin\Parser|\PHPUnit_Framework_MockObject_MockObject $parser */
        $parser = $this->getMockBuilder(Parser::class)
            ->disableOriginalConstructor()
            ->getMock();

        $parser->expects(self::exactly(0))
            ->method('parse');

        $application = new Application($kernel);
        $application->add(new CheckGherkinCodeStyle(null, $parser));

        $command       = $application->find('gherkin:check');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'directory' => __DIR__,
            ]
        );

        self::assertRegExp('/Everything is OK!/', $commandTester->getDisplay());
    }

    public function testShouldReturnErrorIfThereIsFilesWithWrongStyle()
    {
        $kernel = $this->getMock(\stdClass::class);

        /* @var \Behat\Gherkin\Parser|\PHPUnit_Framework_MockObject_MockObject $parser */
        $parser = $this->getMockBuilder(Parser::class)
            ->disableOriginalConstructor()
            ->getMock();

        /* @var \Behat\Gherkin\Node\FeatureNode|\PHPUnit_Framework_MockObject_MockObject $feature */
        $feature = $this->getMockBuilder(FeatureNode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $feature->expects(self::once())
            ->method('hasTags')
            ->willReturn(true);

        $feature->expects(self::once())
            ->method('getTags')
            ->willReturn(
                ['users', 'another-feature', 'another-tag']
            );

        $feature->expects(self::once())
            ->method('getTitle')
            ->willReturn(
                'User registration'
            );

        $feature->expects(self::once())
            ->method('getDescription')
            ->willReturn(
                "In order to order products\n" .
                "As a visitor\n" .
                "I need to be able to create an account in the store"
            );

        $feature->expects(self::once())
            ->method('hasBackground')
            ->willReturn(true);

        $feature->expects(self::once())
            ->method('getBackground')
            ->willReturn($this->getBackground());

        $feature->expects(self::once())
            ->method('hasScenarios')
            ->willReturn(false);

        $parser->expects(self::once())
            ->method('parse')
            ->willReturn($feature);

        $application = new Application($kernel);
        $application->add(new CheckGherkinCodeStyle(null, $parser));

        $command       = $application->find('gherkin:check');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'directory' => __DIR__ . '/../assets/left-aligned.feature',
            ]
        );

        self::assertRegExp('/Wrong style/', $commandTester->getDisplay());
        self::assertNotRegExp('/I need to be able to create an account in the store/', $commandTester->getDisplay());
    }

    /**
     * @return BackgroundNode|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getBackground()
    {
        /* @var \Behat\Gherkin\Node\BackgroundNode|\PHPUnit_Framework_MockObject_MockObject $background */
        $background = $this->getMockBuilder(BackgroundNode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $background->expects(self::once())
            ->method('getKeyword')
            ->willReturn('Background');

        $background->expects(self::once())
            ->method('getTitle')
            ->willReturn('Nice Background');

        $background->expects(self::once())
            ->method('getSteps')
            ->willReturn([
                new StepNode('Given', 'store has default configuration', [], 1),
                new StepNode('And', 'there are following users:', [new TableNode([['email', 'password'], ['bar@bar.com', 'foo1sasdasdasdadsasd']])], 2)
            ]);

        return $background;
    }
}
