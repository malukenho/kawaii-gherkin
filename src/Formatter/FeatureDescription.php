<?php

namespace KawaiiGherkin\Formatter;

final class FeatureDescription
{
    public function format($shortDescription, array $descriptionLines, $indentation = 4)
    {
        $indentation = str_repeat(' ', $indentation);
        $shortDesc   = 'Feature: ' . $shortDescription . PHP_EOL;
        $longDesc    =  implode(
            array_map(
                function ($descriptionLine) use ($indentation) {
                    return $indentation . trim($descriptionLine) . PHP_EOL;
                },
                $descriptionLines
            )
        );

        return $shortDesc . rtrim($longDesc);
    }
}
