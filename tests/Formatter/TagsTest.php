<?php

namespace KawaiiGherkinTest\Formatter;

use KawaiiGherkin\Formatter\Tags;

final class TagsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Tags
     */
    private $formatter;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->formatter = new Tags();
    }

    public function testCanFormatTag()
    {
        $wrongTagsInput = [
            '  user  ',
            '      feature-123',
            'bug-345     ',
        ];
        $expected       = '@user @feature-123 @bug-345';

        self::assertSame($expected, $this->formatter->format($wrongTagsInput));
    }
}
