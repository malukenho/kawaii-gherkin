<?php

declare(strict_types=1);

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\BackgroundNode;

final class Background
{
    private Indentation $indentation;

    private Steps $steps;

    public function __construct(Indentation $indentation, Steps $steps)
    {
        $this->indentation = $indentation;
        $this->steps       = $steps;
    }

    /**
     * @return iterable<string>
     */
    public function format(?BackgroundNode $background): iterable
    {
        if (null === $background) {
            return;
        }

        yield from $this->indentation->format(1);

        yield $background->getKeyword();

        yield ': ';

        yield (string) $background->getTitle();

        yield "\n";

        yield from $this->steps->format($background->getSteps());
    }
}
