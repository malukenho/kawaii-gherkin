<?php

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\ScenarioInterface;

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
