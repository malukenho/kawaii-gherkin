<?php

namespace KawaiiGherkinTest;

use KawaiiGherkin\FeatureResolve;

final class FeatureResolveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider unExistsDirectory
     *
     * @param string $directory
     * @param string $expectedDirectory
     * @param string $expectedFile
     */
    public function testShouldReturnCorrectDirectory(
        $directory,
        $expectedDirectory,
        $expectedFile
    ) {
        $feature = new FeatureResolve($directory);

        self::assertSame($directory, self::getObjectAttribute($feature, 'directoryOrFile'));

        $getDirectoryMethod = new \ReflectionMethod('KawaiiGherkin\FeatureResolve', 'getDirectory');
        $getDirectoryMethod->setAccessible(true);
        self::assertSame($expectedDirectory, $getDirectoryMethod->invoke($feature));

        $getFeatureMatch = new \ReflectionMethod('KawaiiGherkin\FeatureResolve', 'getFeatureMatch');
        $getFeatureMatch->setAccessible(true);
        self::assertSame($expectedFile, $getFeatureMatch->invoke($feature));
    }

    public function unExistsDirectory()
    {
        return [
            ['null/*.feature', 'null', '*.feature'],
            ['foo-bar/none.feature', 'foo-bar', 'none.feature'],
            ['none/foo.text', 'none', 'foo.text'],
            ['foo-barr/bar.feature', 'foo-barr', 'bar.feature'],
            ['bar/biz/boo/feature.feature', 'bar/biz/boo', 'feature.feature'],
        ];
    }
}
