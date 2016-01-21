<?php

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\BackgroundNode;
use Behat\Gherkin\Node\TableNode;

final class Background
{
    public function format(BackgroundNode $background, $indentation = 4)
    {
        $indentationAlign = $indentation * 2;

        $shortDesc   = $this->getBackgroundShortDescription($background) . PHP_EOL;
        $longDesc    = '';

        foreach ($background->getSteps() as $step) {
            $indentSpaces =  $indentationAlign - strlen(trim($step->getKeyword())) + 1;
            $longDesc .= str_repeat(' ', $indentSpaces + $indentation) .
                trim($step->getKeyword()) . ' ' . trim($step->getText()) . PHP_EOL;

            if ($step->hasArguments()) {
                /* @var $argument TableNode */
                foreach ($step->getArguments() as $argument) {
                    if ($argument->getNodeType() === 'Table') {
                            $longDesc .= implode('', array_map(
                                function ($arguments) use ($indentationAlign) {
                                    return str_repeat(' ', $indentationAlign + 4) . trim($arguments) . PHP_EOL;
                                },
                                explode("\n", $argument->getTableAsString())
                            )
                        );
                    }
                }
            }
        }

        return str_repeat(' ', $indentation) . $shortDesc . rtrim($longDesc);
    }

    /**
     * @param BackgroundNode $background
     *
     * @return string
     */
    private function getBackgroundShortDescription(BackgroundNode $background)
    {
        return sprintf('%s: %s', trim($background->getKeyword()), trim($background->getTitle()));
    }
}
