<?php

declare(strict_types=1);

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\ScenarioInterface;

final class Scenarios
{
    private Indentation $indentation;

    private Tags $tags;

    private Steps $steps;

    private Examples $examples;

    public function __construct(
        Indentation $indentation,
        Tags $tags,
        Steps $steps,
        Examples $examples
    ) {
        $this->indentation = $indentation;
        $this->tags        = $tags;
        $this->steps       = $steps;
        $this->examples    = $examples;
    }

    /**
     * @param ScenarioInterface[] $scenarios
     *
     * @return iterable<string>
     */
    public function format(array $scenarios): iterable
    {
        foreach ($scenarios as $scenario) {
            yield from $this->getTags($scenario);
            yield from $this->getScenarioDescription($scenario);
            yield from $this->getSteps($scenario);
            yield from $this->getExamples($scenario);
        }

        yield "\n";
    }

    /**
     * @return iterable<string>
     */
    private function getTags(ScenarioInterface $scenario): iterable
    {
        if ($scenario->hasTags()) {
            yield from $this->tags->format($scenario->getTags());
            yield "\n";
        }
    }

    /**
     * @return iterable<string>
     */
    private function getSteps(ScenarioInterface $scenario): iterable
    {
        if ($scenario->hasSteps()) {
            yield from $this->steps->format($scenario->getSteps());
            yield "\n";
        }
    }

    /**
     * @return iterable<string>
     */
    private function getExamples(ScenarioInterface $scenario): iterable
    {
        if ($scenario instanceof OutlineNode) {
            yield from $this->examples->format($scenario);
        }
    }

    /**
     * @return iterable<string>
     */
    private function getScenarioDescription(ScenarioInterface $scenario): iterable
    {
        yield from $this->indentation->format(1);
        yield $scenario->getKeyword();
        yield ': ';
        yield from $this->getTitleLines($scenario);

        yield "\n";
    }

    /**
     * @return iterable<string>
     */
    private function getTitleLines(ScenarioInterface $scenario): iterable
    {
        foreach (explode("\n", (string) $scenario->getTitle()) as $word) {
            yield trim($word);
        }
    }
}
