<?php

require  __DIR__ . '/../../vendor/autoload.php';

/**
 * @param string $params
 */
$kawaiiGherkinCheck = function ($params) {

    if (!is_string($params)) {
        PHPUnit_Framework_Assert::markTestSkipped(
            sprintf(
                '$param was expected to be a "string", "%s" given.',
                gettype($params)
            )
        );
    }

    $basePath = realpath(__DIR__ . '/../../');

    system("$basePath/bin/kawaii gherkin:check $params");
};
