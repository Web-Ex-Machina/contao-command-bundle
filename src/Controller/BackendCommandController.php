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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Component\Console\Output\OutputInterface;

#[IsGranted('ROLE_ADMIN', message: 'Access restricted to administrators.')]
class BackendCommandController extends AbstractBackendController
{
    public function __construct(private readonly KernelInterface $kernel)
    {
    }

    #[Route('%contao.backend.route_prefix%/commands', name: self::class, defaults: ['_scope' => 'backend'])]
    public function __invoke(Request $request): Response
    {
        $commandes = [
            'about' => 'À propos de Contao',
            'cache-clear' => 'Vider le cache',
            'cache-warmup' => 'Recharger le cache',
            'symlinks' => 'Reconstruire les liens symboliques',
            'resize-images' => 'Redimensionner les images qui ne le seraient pas',

            'debug-autowiring' => 'List classes/interfaces you can use for autowiring',
            'debug-config' => 'Dump the current configuration for an extension',
            'debug-container' => 'Display current services for an application',
            'debug-contao-twig' => 'Displays the Contao template hierarchy.',
            'debug-dca' => 'Dumps the DCA configuration for a table.',
            'debug-dotenv' => 'List all dotenv files with variables and values',
            'debug-event-dispatcher' => 'Display configured listeners for an application',
            'debug-firewall' => 'Display information about your security firewall(s)',
            'debug-fragments' => 'Displays the fragment controller configuration.',
            'debug-messenger' => 'List messages you can dispatch using the message buses',
            'debug-pages' => 'Displays the page controller configuration.',
            'debug-plugins' => 'Displays the Contao Manager plugin configurations.',
            'debug-router ' => 'Display current routes for an application',
            'debug-translation' => 'Display translation messages information',
            'debug-twig' => 'Show a list of twig functions, filters, globals and tests',

            'env-dump' => 'Compiler les fichiers .env vers .env.local.php',
            'lint-container' => 'Verifier la validité du conteneur de configuration',
//            'mailer-test' => 'Test mailer',
            'router-match' => 'Afficher la listes des routes disponibles.'
        ];

        $commande = $request->query->get('commande');
        $content="";
        if (isset($commande)) {
            $application = new Application($this->kernel);
            $application->setAutoExit(false);

            $trueCommande = match($commande){
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
                'debug-messenger'  => 'debug:messenger',
                'debug-pages' => 'debug:pages',
                'debug-plugins' => 'debug:plugins',
                'debug-router ' => 'debug:router',
                'debug-translation'  => 'debug:translation',
                'debug-twig' => 'debug:twig',
                
                default => 'list'
            };


            $input = new ArrayInput([
                'command' => $trueCommande,
                // (optional) define the value of command arguments
                //'fooArgument' => 'barValue',
                // (optional) pass options to the command
                '--env' => 'prod',
                // (optional) pass options without value
                '--no-debug' => true,
            ]);

            \set_time_limit(300);
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
            'title' => 'Lancer une commande',
            'headline' => 'Lancer une commande',
            'commandes' => $commandes,
            'retour'=> isset($converter)?$converter->convert($content):false
        ]);
    }
}
