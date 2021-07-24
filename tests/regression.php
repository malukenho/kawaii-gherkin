#!/usr/bin/env php
<?php

use SebastianBergmann\Diff\Differ;

require dirname(__FILE__, 2).'/vendor/autoload.php';

$fix = static function (string $path, string $align): string {
    $tmp = (string) tempnam(sys_get_temp_dir(), 'gherkin_');

    copy($path, $tmp);

    exec(dirname(__DIR__).'/bin/kawaii fix '.$tmp.' --align='.$align);

    return (string) file_get_contents($tmp);
};

foreach (glob(__DIR__.'/regression/cases/*/original.gherkin') ?: [] as $originalPath) {
    $files = [
        'left'  => dirname($originalPath).'/result-left.gherkin',
        'right' => dirname($originalPath).'/result-right.gherkin',
    ];

    foreach ($files as $align => $filePath) {
        if (false === file_exists($filePath)) {
            file_put_contents(
                $filePath,
                $fix($originalPath, $align)
            );
        }

        $expectation = (string) file_get_contents($filePath);
        $reality     = $fix($originalPath, $align);

        if ($expectation === $reality) {
            continue;
        }

        $diff = (new Differ())->diff($expectation, $reality);

        throw new Exception(<<<ERROR
            Failure when fixing {$originalPath} with {$align} alignment:

            {$diff}
            ERROR);
    }
}
