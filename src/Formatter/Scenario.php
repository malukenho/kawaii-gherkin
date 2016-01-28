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

use Behat\Gherkin\Node\ScenarioInterface;

/**
 * @author Jefersson Nathan  <malukenho@phpse.net>
 * @license MIT
 */
final class Scenario
{
    /**
     * TODO: Abstract it method
     */
    const INDENTATION = 4;

    /**
     * @param \Behat\Gherkin\Node\ScenarioInterface[] ...$scenarios
     *
     * @return string
     */
    public function format(ScenarioInterface ...$scenarios)
    {
        return rtrim(implode(
            '',
            array_map(
                function (ScenarioInterface $scenario) {
                    return $this->indent()
                    . $this->getTags($scenario)
                    . $this->getScenarioDescription($scenario)
                    . $this->getSteps($scenario);
                },
                $scenarios
            )
        )) . PHP_EOL;
    }

    private function getTags(ScenarioInterface $scenario)
    {
        if (! $scenario->hasTags()) {
            return;
        }

        return (new Tags())->format($scenario->getTags()) . PHP_EOL;
    }

    private function getScenarioDescription(ScenarioInterface $scenario)
    {
        return
            ($scenario->hasTags() ? $this->indent() : '')
            . sprintf(
                '%s: %s%s',
                trim($scenario->getKeyword()),
                trim($scenario->getTitle()),
                PHP_EOL
            );
    }

    /**
     * TODO: abstract this method
     *
     * @param int $spaceQuantity
     * @return string
     */
    private function indent($spaceQuantity = self::INDENTATION)
    {
        return str_repeat(' ', $spaceQuantity);
    }

    private function getSteps(ScenarioInterface $scenario)
    {
        if (! $scenario->hasSteps()) {
            return;
        }

        $recue = self::INDENTATION * 2;
        // TODO: abstract scenario formatter logic
        $longDesc = '';
        foreach ($scenario->getSteps() as $step) {
            $indentSpaces =  $recue - strlen(trim($step->getKeyword())) + 1;
            $longDesc .= str_repeat(' ', $indentSpaces + self::INDENTATION) .
                trim($step->getKeyword()) . ' ' . trim($step->getText()) . PHP_EOL;

            if ($step->hasArguments()) {
                /* @var $argument TableNode */
                foreach ($step->getArguments() as $argument) {
                    if ($argument->getNodeType() === 'Table') {
                        $longDesc .= implode('', array_map(
                                function ($arguments) use ($recue) {
                                    return str_repeat(' ', $recue + 4) . trim($arguments) . PHP_EOL;
                                },
                                explode("\n", $argument->getTableAsString())
                            )
                        );
                    }
                }
            }
        }

        return $longDesc . PHP_EOL;
    }
}
