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

namespace WEM\CommandBundle\EventListener;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\MenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use WEM\CommandBundle\Controller\BackendCommandController;

#[AsEventListener(ContaoCoreEvents::BACKEND_MENU_BUILD, priority: -255)]
final readonly class CommandMenuListener
{
    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    public function __invoke(MenuEvent $event): void
    {
        $factory = $event->getFactory();
        $tree = $event->getTree();

        if ($tree->getName() !== 'mainMenu') {
            return;
        }

        $contentNode = $tree->getChild('content');

        $node = $factory
            ->createItem('my-module', ['route' => BackendCommandController::class])
            ->setLabel('My Modules')
            ->setLinkAttribute('title', 'Title')
            ->setLinkAttribute('class', 'my-module')
            ->setCurrent(
                $this->requestStack->getCurrentRequest()->get('_controller') === BackendCommandController::class
            )
        ;

        $contentNode->addChild($node);
    }
}
