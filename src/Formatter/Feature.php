<?php

declare(strict_types=1);

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\FeatureNode;

final class Feature
{
    private Background $background;

    private Scenarios $scenarios;

    private FeatureDescription $featureDescription;

    private Tags $tags;

    public function __construct(
        Background $background,
        Scenarios $scenarios,
        FeatureDescription $featureDescription,
        Tags $tags
    ) {
        $this->background         = $background;
        $this->scenarios          = $scenarios;
        $this->featureDescription = $featureDescription;
        $this->tags               = $tags;
    }

    /**
     * @return iterable<string>
     */
    public function format(?FeatureNode $feature): iterable
    {
        if (null === $feature) {
            return;
        }

        if (false === \in_array($feature->getLanguage(), ['', 'en'], true)) {
            yield '# language: ';
            yield $feature->getLanguage();
            yield "\n";
        }

        if ($feature->hasTags()) {
            yield from $this
                ->tags
                ->format($feature->getTags())
            ;
            yield "\n";
        }

        yield from $this
            ->featureDescription
            ->format($feature)
        ;
        yield "\n";
        yield "\n";

        if ($feature->hasBackground()) {
            yield from $this
                ->background
                ->format($feature->getBackground())
            ;
            yield "\n";
        }

        if ($feature->hasScenarios()) {
            yield from $this
                ->scenarios
                ->format($feature->getScenarios())
            ;
        }
    }
}
