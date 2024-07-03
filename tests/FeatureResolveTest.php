<?php

namespace KawaiiGherkinTest;

use KawaiiGherkin\FeatureResolve;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FeatureResolveTest extends TestCase
{
    #[Test]
    #[DataProvider('unExistsDirectory')]
    public function itShouldReturnCorrectDirectory(
        string $directory,
        string $expectedDirectory,
        string $expectedFile
    ): void {
        $feature = new FeatureResolve($directory);

        self::assertObjectHasProperty('directoryOrFile', $feature);

        $getDirectoryMethod = new \ReflectionMethod($feature, 'getDirectory');
        $getDirectoryMethod->setAccessible(true);
        self::assertSame($expectedDirectory, $getDirectoryMethod->invoke($feature));

        $getFeatureMatch = new \ReflectionMethod($feature, 'getFeatureMatch');
        $getFeatureMatch->setAccessible(true);
        self::assertSame($expectedFile, $getFeatureMatch->invoke($feature));
    }

    public static function unExistsDirectory(): array
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
