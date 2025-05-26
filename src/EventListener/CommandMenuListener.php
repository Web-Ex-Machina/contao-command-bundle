<?php
namespace WEM\CommandBundle\EventListener;

use WEM\CommandBundle\Controller\BackendCommandController;
use Contao\CoreBundle\Event\MenuEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Contao\CoreBundle\Event\ContaoCoreEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(ContaoCoreEvents::BACKEND_MENU_BUILD, priority: -255)]
final readonly class CommandMenuListener
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function __invoke(MenuEvent $event): void
    {
        $factory = $event->getFactory();
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName()) {
            return;
        }

        $contentNode = $tree->getChild('content');

        $node = $factory
            ->createItem('my-module', ['route' => BackendCommandController::class])
            ->setLabel('My Modules')
            ->setLinkAttribute('title', 'Title')
            ->setLinkAttribute('class', 'my-module')
            ->setCurrent($this->requestStack->getCurrentRequest()->get('_controller') === BackendCommandController::class)
        ;

        $contentNode->addChild($node);
    }
}