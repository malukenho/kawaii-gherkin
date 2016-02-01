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

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\ArgumentInterface;
use Behat\Gherkin\Node\StepNode;

/**
 * @author  Jefersson Nathan  <malukenho@phpse.net>
 * @license MIT
 */
final class Step extends AbstractFormatter
{
    const ALIGN_TO_LEFT  = 'left';
    const ALIGN_TO_RIGHT = 'right';

    /**
     * @var string
     */
    private $align;

    /**
     * @param string $align
     */
    public function __construct($align = self::ALIGN_TO_RIGHT)
    {
        $this->align = $align;
    }

    /**
     * @param \Behat\Gherkin\Node\StepNode[] $steps
     *
     * @return string
     */
    public function format(array $steps)
    {
        return implode(
            array_map(
                function (StepNode $step) {
                    return $this->getStepText($step) . $this->getArguments($step);
                },
                $steps
            )
        );
    }

    /**
     * @param string $keyword
     *
     * @return int
     */
    private function computeIndentationSpaces($keyword)
    {
        if ($this->align === self::ALIGN_TO_RIGHT) {
            return (self::INDENTATION * 2) - strlen(trim($keyword)) + 1;
        }

        return self::INDENTATION;
    }

    /**
     * @param StepNode $step
     *
     * @return string
     */
    private function getStepText(StepNode $step)
    {
        $spacesQuantity = $this->computeIndentationSpaces($step->getKeyword());

        return rtrim(sprintf(
            '%s%s %s',
            $this->indent($spacesQuantity + self::INDENTATION),
            trim($step->getKeyword()),
            trim($step->getText())
        )) . PHP_EOL;

    }

    /**
     * @param StepNode $step
     *
     * @return string
     */
    private function getArguments(StepNode $step)
    {
        if (! $step->hasArguments()) {
            return;
        }

        return implode(
            array_map(
                function (ArgumentInterface $argument) {
                    if (in_array($argument->getNodeType(), ['Table', 'ExampleTable'])) {
                        return implode(
                            array_map(
                                function ($arguments) {
                                    return $this->indent(self::INDENTATION * 2 + 4) . trim($arguments) . PHP_EOL;
                                },
                                explode(PHP_EOL, $argument->getTableAsString())
                            )
                        );
                    }

                    if ('PyString' === $argument->getNodeType()) {
                        return $this->encapsulateAsPyString(
                            implode(
                                array_map(
                                    function ($arguments) {
                                        return rtrim($this->indent(self::INDENTATION * 2 + 2) . trim($arguments)) . PHP_EOL;
                                    },
                                    $argument->getStrings()
                                )
                            )
                        );
                    }
                },
                $step->getArguments()
            )
        );
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private function encapsulateAsPyString($string)
    {
        return sprintf('%s%s%1$s', $this->indent(8) . '"""' . PHP_EOL, $string);
    }
}
