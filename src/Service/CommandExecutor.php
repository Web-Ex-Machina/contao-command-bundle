<?php

declare(strict_types=1);

/*
 * Command Bundle for Contao 5 Open Source CMS
 * Copyright (c) 2025-2025 Web ex Machina
 *
 * @category ContaoBundle
 * @package  Web-Ex-Machina/contao-command-bundle
 * @author   Web ex Machina <contact@webexmachina.fr>
 * @link     https://github.com/Web-Ex-Machina/contao-command-bundle/
 */

namespace WEM\CommandBundle\Service;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final readonly class CommandExecutor
{
    public function __construct(
        private string $projectDir
    ) {
    }

    /**
     * Execute a command through PHP.
     *
     * @param string $strCmd [Check https://docs.contao.org/dev/reference/commands/ for available commands]
     *
     * @return string The command's output
     */
    #[\Deprecated('Use the service ContaoUtilsCommand instead')]
    public function executeCmdPHP(string $strCmd): string
    {
        // Finally, clean the Contao cache
        $strConsolePath = $this->projectDir . '/vendor/bin/contao-console';
        $cmd = \sprintf('%s/php -q %s %s --env=prod', PHP_BINDIR, $strConsolePath, $strCmd);

        return self::executeCmd($cmd);
    }

    /**
     * Execute the given command.
     *
     * @param string $cmd     The command to execute
     * @param ?int   $timeout The timeout in seconds (3600 by default)
     *
     * @return string The command's output
     */
    public function executeCmd(string $cmd, int|null $timeout = 3600): string
    {
        $process = method_exists(Process::class, 'fromShellCommandline') ? Process::fromShellCommandline(
            $cmd
        ) : new Process([$cmd]);
        $process->setTimeout($timeout);
        $process->run();

        $i = 0;

        while ($i <= $process->getTimeout()) {
            sleep(1);
            if ($process->isTerminated()) {
                if (! $process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                return $process->getOutput();
            }

            ++$i;
        }

        return $process->getOutput();
    }

    /**
     * Execute the given command by displaying console output live to the user.
     *
     * @param string $cmd     The command to execute
     * @param ?int   $timeout The timeout in seconds (3600 by default)
     *
     * @return string The command's output
     */
    public function executeCmdLive(string $cmd, int|null $timeout = 3600): string
    {
        // while (@ob_end_flush()) { } // end all output buffers if any
        $process = method_exists(Process::class, 'fromShellCommandline') ? Process::fromShellCommandline(
            $cmd
        ) : new Process([$cmd]);
        $process->setTimeout($timeout);
        $process->run(
            static function ($type, $buffer): void {
                if ($type === Process::ERR) {
                    echo json_encode(['data' => $buffer,
                        'status' => 'error']) . ',';
                } else {
                    echo json_encode(['data' => $buffer,
                        'status' => 'success']) . ',';
                }

                @flush();
            }
        );

        $i = 0;

        while ($i <= $process->getTimeout()) {
            sleep(1);
            if ($process->isTerminated()) {
                if (! $process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                return $process->getOutput();
            }

            ++$i;
        }

        return $process->getOutput();
    }
}
