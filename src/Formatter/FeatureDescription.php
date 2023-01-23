<?php

declare(strict_types=1);

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\FeatureNode;

final class FeatureDescription
{
    private Indentation $indentation;

    public function __construct(Indentation $indentation)
    {
        $this->indentation = $indentation;
    }

    /**
     * @return iterable<string>
     */
    public function format(FeatureNode $feature): iterable
    {
        yield $feature->getKeyword();

        yield ': ';

        yield (string) $feature->getTitle();

        if ($feature->hasDescription()) {
            foreach (explode("\n", (string) $feature->getDescription()) as $descriptionLine) {
                yield "\n";

                yield from $this->indentation->format(1);

                yield trim($descriptionLine);
            }
        }
    }
}
