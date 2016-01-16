<?php

namespace KawaiiGherkin\Formatter;

final class Tags
{
    public function format(array $tags)
    {
        return implode(' ',
            array_map(
                function ($tag) {
                    return '@' . trim($tag);
                },
                $tags
            )
        );
    }
}
