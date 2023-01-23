<?php

declare(strict_types=1);

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;

final class Steps
{
    private Indentation $indentation;

    private string $alignement;

    public function __construct(Indentation $indentation, string $alignement)
    {
        $this->indentation = $indentation;
        $this->alignement  = $alignement;
    }

    /**
     * @param StepNode[] $steps
     *
     * @return iterable<string>
     */
    public function format(array $steps): iterable
    {
        foreach ($steps as $step) {
            if ('right' === $this->alignement) {
                yield from $this->indentation->format(2, 5 - \strlen(trim($step->getKeyword())));
            } else {
                yield from $this->indentation->format(2, 0);
            }

            yield trim($step->getKeyword());

            yield ' ';

            yield trim($step->getText());

            yield "\n";

            if (false === $step->hasArguments()) {
                continue;
            }

            foreach ($step->getArguments() as $argument) {
                if ($argument instanceof TableNode) {
                    foreach (explode("\n", $argument->getTableAsString()) as $row) {
                        yield from $this->indentation->format(3);

                        yield trim($row);

                        yield "\n";
                    }
                }

                if ($argument instanceof PyStringNode) {
                    yield from $this->indentation->format(2, 2);

                    yield '"""';

                    yield "\n";
                    foreach ($argument->getStrings() as $string) {
                        $string = rtrim($string);

                        if ('' !== $string) {
                            yield from $this->indentation->format(2, 2);

                            yield rtrim($string);
                        }

                        yield "\n";
                    }

                    yield from $this->indentation->format(2, 2);

                    yield '"""';

                    yield "\n";
                }
            }
        }
    }
}
