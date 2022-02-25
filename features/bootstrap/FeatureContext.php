<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use Symfony\Component\Finder\Finder;

class FeatureContext implements Context
{
    private string $dirPath;

    private string $filePath;

    /**
     * @AfterScenario
     */
    public function reset(): void
    {
        foreach (Finder::create()->files()->in($this->dirPath) as $file) {
            unlink($file->getPathname());
        }
    }

    /**
     * @Given the gherkin file
     */
    public function theGherkinFile(PyStringNode $string): void
    {
        $this->dirPath  = __DIR__.DIRECTORY_SEPARATOR.'files';
        $this->filePath = $this->dirPath.DIRECTORY_SEPARATOR.md5($string->getRaw()).'.feature';

        if (false === is_dir($this->dirPath)) {
            mkdir($this->dirPath);
        }

        touch($this->filePath);
        file_put_contents($this->filePath, $string->getRaw());
    }

    /**
     * @When /^I run (.+) --align (.+)$/
     */
    public function iRunKawaii(string $command, string $align): void
    {
        exec("bin/kawaii {$command} --align {$align} {$this->filePath}", $output, $code);

        if (0 !== $code) {
            throw new Exception(implode("\n", $output));
        }
    }

    /**
     * @Then the file should contain
     */
    public function theFileShouldContain(PyStringNode $string): void
    {
        $content = (string) file_get_contents($this->filePath);
        $content = rtrim($content, "\n");

        if ($content === $string->getRaw()) {
            return;
        }

        $outputBuilder = new UnifiedDiffOutputBuilder("--- Original\n+++ Expected\n", false);
        $diff          = new Differ($outputBuilder);

        $output = $diff->diff($string->getRaw(), $content);

        throw new Exception($output);
    }
}
