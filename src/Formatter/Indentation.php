<?php

declare(strict_types=1);

namespace KawaiiGherkin\Formatter;

final class Indentation
{
    private int $indentationSize;

    public function __construct(int $indentationSize)
    {
        $this->indentationSize = $indentationSize;
    }

    /**
     * @return iterable<string>
     */
    public function format(int $index, int $extra = 0): iterable
    {
        yield str_repeat(' ', ($index * $this->indentationSize) + $extra);
    }
}
