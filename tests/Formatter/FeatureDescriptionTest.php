<?php

namespace KawaiiGherkinTest\Formatter;

use KawaiiGherkin\Formatter\FeatureDescription;

final class FeatureDescriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FeatureDescription
     */
    private $formatter;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->formatter = new FeatureDescription();
    }

    public function testCanFormatFeatureDescription()
    {
        $expected =<<<EOS
Feature: How to be a kawai person
    In order to be a kawai person
    As a non-kawai person
    I have to turn me a PHP programmer
EOS;

        $shortDescription = 'How to be a kawai person';
        $longDescription  = [
            '           In order to be a kawai person',
            'As a non-kawai person                  ',
            '                   I have to turn me a PHP programmer',
        ];

        self::assertSame($expected, $this->formatter->format($shortDescription, $longDescription));
    }
}
