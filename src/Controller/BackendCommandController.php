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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('%contao.backend.route_prefix%/commands', name: self::class, defaults: ['_scope' => 'backend'])]
class BackendCommandController extends AbstractBackendController
{
    public function __invoke(): Response
    {
        return $this->render('backcommands.html.twig', [
            'error' => 'Oh no, an error!',
            'title' => 'My title',
            'headline' => 'My headline',
            'version' => 'I can overwrite what I want',
            'foo' => 'bar',
        ]);
    }
}
