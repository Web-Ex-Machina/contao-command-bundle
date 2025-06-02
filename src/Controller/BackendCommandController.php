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

namespace WEM\CommandBundle\Controller;

use Contao\CoreBundle\Controller\AbstractBackendController;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('ROLE_ADMIN', message: 'Access restricted to administrators.')]
class BackendCommandController extends AbstractBackendController
{
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly TranslatorInterface $translator
    ) {
    }

    #[Route('%contao.backend.route_prefix%/commands', name: self::class, defaults: ['_scope' => 'backend'])]
    public function __invoke(Request $request): Response
    {
        $commandes = [
            'about' => 'select.about',
            'cache-clear' => 'select.cache-clear',
            'cache-warmup' => 'select.cache-warmup',
            'symlinks' => 'select.symlinks',
            'resize-images' => 'select.resize-images',

            'debug-autowiring' => 'select.debug.autowiring',
            'debug-config' => 'select.debug.config',
            'debug-container' => 'select.debug.container',
            'debug-contao-twig' => 'select.debug.contao-twig',
            'debug-dca' => 'select.debug.dca',
            'debug-dotenv' => 'select.debug.dotenv',
            'debug-event-dispatcher' => 'select.debug.event-dispatcher',
            'debug-firewall' => 'select.debug.firewall',
            'debug-fragments' => 'select.debug.fragments',
            'debug-messenger' => 'select.debug.messenger',
            'debug-pages' => 'select.debug.pages',
            'debug-plugins' => 'select.debug.plugins',
            'debug-router ' => 'select.debug.router',
            'debug-translation' => 'select.debug.translation',
            'debug-twig' => 'select.debug.twig',

            'env-dump' => 'select.env-dump',
            'lint-container' => 'select.lint-container',
            // 'mailer-test' => 'Test mailer',
            'router-match' => 'select.router-match',
        ];

        $commande = $request->query->get('commande');
        $content = '';
        if (isset($commande)) {
            $application = new Application($this->kernel);
            $application->setAutoExit(false);

            $trueCommande = match ($commande) {
                'about' => 'about',
                'cache-clear' => 'cache:clear',
                'cache-warmup' => 'cache:warmup',
                'symlinks' => 'contao:symlinks',
                'resize-images' => 'contao:resize-images',
                'debug' => 'debug:*',
                'env-dump' => 'dotenv:dump',
                'lint-container' => 'lint:container',
                'mailer-test' => 'mailer:test',
                'router-match' => 'router:match',

                'debug-autowiring' => 'debug:autowiring',
                'debug-config' => 'debug:config',
                'debug-container' => 'debug:container',
                'debug-contao-twig' => 'debug:contao-twig',
                'debug-dca' => 'debug:dca',
                'debug-dotenv' => 'debug:dotenv',
                'debug-event-dispatcher' => 'debug:event-dispatcher',
                'debug-firewall' => 'debug:firewall',
                'debug-fragments' => ' debug:fragments',
                'debug-messenger' => 'debug:messenger',
                'debug-pages' => 'debug:pages',
                'debug-plugins' => 'debug:plugins',
                'debug-router ' => 'debug:router',
                'debug-translation' => 'debug:translation',
                'debug-twig' => 'debug:twig',

                default => 'list'
            };

            $input = new ArrayInput([
                'command' => $trueCommande,
                // (optional) define the value of command arguments fooArgument' => 'barValue',
                // (optional) pass options to the command
                '--env' => 'prod',
                // (optional) pass options without value
                '--no-debug' => true,
            ]);

            set_time_limit(300);
            // You can use NullOutput() if you don't need the output
            $output = new BufferedOutput(
                OutputInterface::VERBOSITY_NORMAL,
                true // true for decorated
            );
            $application->run($input, $output);

            // return the output, don't use if you used NullOutput()
            $converter = new AnsiToHtmlConverter();

            $content = $output->fetch();
        }

        return $this->render('@Contao/command_center/commands.html.twig', [
            'title' => $this->translator->trans('title.launch-command', [], 'CommandBundle'),
            'headline' => $this->translator->trans('title.headline-command', [], 'CommandBundle'),
            'commandes' => $commandes,
            'retour' => isset($converter) ? $converter->convert($content) : false,
        ]);
    }
}
