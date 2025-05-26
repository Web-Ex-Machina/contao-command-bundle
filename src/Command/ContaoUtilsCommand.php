<?php

namespace WEM\CommandBundle\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'contao:command', description: 'Contao 2 generic command')]
class ContaoUtilsCommand extends Command
{
    public function __construct(
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(name: 'command', mode: InputArgument::REQUIRED, description: 'Option description',
                suggestedValues: [
                    "about",
                    "list",
                    "cache-clear",
                    "cache-warmup",
                    "contao-symlinks",
                    "contao-resize-images",
                    "debug",
                    "env-dump",
                    "lint",
                    "mailer-test",
                    "router-match",
                ]);
    }

    /**
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //$io = new SymfonyStyle($input, $output);
        $command = $input->getArgument('command');

        $command = match ($command) { // no "list", it’s normal, fallback to default.
            "about" => "contao-console about",
            "cache-clear" => "contao-console cache:clear",
            "cache-warmup" => "contao-console cache:warmup",
            "symlinks" => "contao-console contao:symlinks",
            "resize-images" => "contao-console contao:resize-images",
            "debug" => "contao-console debug:*",
            "env-dump" => "contao-console dotenv:dump",
            "lint" => "contao-console lint:*",
            "mailer-test" => "contao-console mailer:test",
            "router-match" => "contao-console router:match",
            default => "contao-console list"
        };

        $greetInput = new ArrayInput(['command' => $command]);

        $greetInput->setInteractive(false);

        return $this->getApplication()->doRun($greetInput, $output);

    }
}