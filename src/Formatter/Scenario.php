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

use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\ScenarioInterface;

/**
 * @author  Jefersson Nathan  <malukenho@phpse.net>
 * @license MIT
 */
final class Scenario extends AbstractFormatter
{
    private $align;

    /**
     * @param string $align
     */
    public function __construct($align = Step::ALIGN_TO_RIGHT)
    {
        $this->align = $align;
    }

    /**
     * @param \Behat\Gherkin\Node\ScenarioInterface[] $scenarios
     *
     * @return string
     */
    public function format(array $scenarios)
    {
        return rtrim(implode(
            array_map(
                function (ScenarioInterface $scenario) {
                    return $this->getTags($scenario)
                    . $this->getScenarioDescription($scenario)
                    . $this->getSteps($scenario)
                    . $this->getExamples($scenario);
                },
                $scenarios
            )
        )) . "\n";
    }

    /**
     * @param ScenarioInterface $scenario
     *
     * @return string
     */
    private function getTags(ScenarioInterface $scenario)
    {
        if (! $scenario->hasTags()) {
            return $this->indent();
        }

        return $this->indent() . (new Tags())->format($scenario->getTags()) . "\n";
    }

    /**
     * @param ScenarioInterface $scenario
     *
     * @return string
     */
    private function getScenarioDescription(ScenarioInterface $scenario)
    {
        $titleLines    = $this->getTitleLines($scenario);
        $scenarioTitle = trim($scenario->getKeyword() . ': ' . array_shift($titleLines));

        if ($scenario->hasTags()) {
            $scenarioTitle = $this->indent() . $scenarioTitle;
        }

        return implode(
            "\n" . $this->indent(12),
            array_merge(
                [$scenarioTitle],
                $titleLines
            )
        ) . "\n";
    }

    /**
     * @param ScenarioInterface $scenario
     *
     * @return string|void
     */
    private function getSteps(ScenarioInterface $scenario)
    {
        if (! $scenario->hasSteps()) {
            return;
        }

        $step = new Step($this->align);

        return $step->format($scenario->getSteps()) . "\n";
    }

    /**
     * @param $scenario
     *
     * @return string|void
     */
    private function getExamples($scenario)
    {
        if (! $scenario instanceof OutlineNode) {
            return;
        }

        return (new Example())->format($scenario);
    }

    /**
     * @param ScenarioInterface $scenario
     * @return array
     */
    private function getTitleLines(ScenarioInterface $scenario)
    {
        return array_map(
            'trim',
            explode("\n", $scenario->getTitle())
        );
    }
}
