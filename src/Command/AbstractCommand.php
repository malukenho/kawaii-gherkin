<?php

declare(strict_types=1);

namespace KawaiiGherkin\Command;

use KawaiiGherkin\DIC;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractCommand extends Command
{
    public const SUCCESS = 0;

    public const FAILURE = 1;

    protected DIC $dic;

    public function __construct(DIC $dic)
    {
        $this->dic = $dic;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'sources',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'Path to find *.feature files'
            )
            ->addOption(
                'align',
                'a',
                InputOption::VALUE_OPTIONAL,
                'Side to align statement (right or left). Default right',
                'left'
            )
            ->addOption(
                'indent',
                'i',
                InputOption::VALUE_OPTIONAL,
                'Side to align statement (right or left). Default right',
                '4'
            )
        ;
    }
}
