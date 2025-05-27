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
            'cache-clear' => 'contao-console cache:clear',
            'cache-warmup' => 'contao-console cache:warmup',
            'symlinks' => 'contao-console contao:symlinks',
            'resize-images' => 'contao-console contao:resize-images',
            'debug' => 'contao-console debug:*',
            'env-dump' => 'contao-console dotenv:dump',
            'lint' => 'contao-console lint:*',
            'mailer-test' => 'contao-console mailer:test',
            'router-match' => 'contao-console router:match'
        ];

        $commande = $request->query->get('commande');
        if ($commande) {
            $application = new Application($this->kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'about',
                // (optional) define the value of command arguments
                //'fooArgument' => 'barValue',
                // (optional) pass options to the command
                //'--bar' => 'fooValue',
                // (optional) pass options without value
                //'--baz' => true,
            ]);

            // You can use NullOutput() if you don't need the output
            $output = new BufferedOutput(
                OutputInterface::VERBOSITY_NORMAL,
                true // true for decorated
            );
            $application->run($input, $output);

            // return the output, don't use if you used NullOutput()
            $converter = new AnsiToHtmlConverter();
            $content = $output->fetch();

            // return new Response(""), if you used NullOutput()
            return new Response($converter->convert($content));
        }

        return $this->render('@Contao/command_center/commands.html.twig', [
            'title' => 'Lancer une commande',
            'headline' => 'Lancer une commande',
            //'version' => 'I can overwrite what I want',
            'commandes' => $commandes,
        ]);
    }
}
