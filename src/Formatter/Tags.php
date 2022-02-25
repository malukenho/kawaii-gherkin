<?php

declare(strict_types=1);

namespace KawaiiGherkin\Formatter;

final class Tags
{
    /**
     * @param array<string> $tags
     *
     * @return iterable<string>
     */
    public function format(array $tags): iterable
    {
        if (empty($tags)) {
            return;
        }

        yield '@';
        yield trim(array_shift($tags));

        while (false === empty($tags)) {
            yield ' ';
            yield '@';
            yield trim((string) array_shift($tags));
        }
    }
}
