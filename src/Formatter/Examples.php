<?php

declare(strict_types=1);

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\OutlineNode;

final class Examples
{
    private Indentation $indentation;

    public function __construct(Indentation $indentation)
    {
        $this->indentation = $indentation;
    }

    /**
     * @return iterable<string>
     */
    public function format(OutlineNode $scenario): iterable
    {
        if (false === $scenario->hasExamples()) {
            return;
        }

        yield from $this->indentation->format(2);

        yield rtrim($scenario->getExampleTable()->getKeyword());

        yield ':';

        yield "\n";

        foreach (explode("\n", $scenario->getExampleTable()->getTableAsString()) as $arguments) {
            yield from $this->indentation->format(2, 2);

            yield trim($arguments);

            yield "\n";
        }

        yield "\n";
    }
}
