<?php

namespace WEM\CommandBundle\Controller;

use Contao\CoreBundle\Controller\AbstractBackendController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('%contao.backend.route_prefix%/commands', name: self::class, defaults: ['_scope' => 'backend'])]
class BackendCommandController extends AbstractBackendController
{
    public function __invoke(): Response
    {
        return $this->render(
            'commands.html.twig',
            [
                'error' => 'Oh no, an error!',
                'title' => 'My title',
                'headline' => 'My headline',
                'version' => 'I can overwrite what I want',
                'foo' => 'bar',
            ]
        );
    }
}