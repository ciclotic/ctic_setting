<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Base\Domain\EntityInterface;

final class ResourceUpdateHandler implements ResourceUpdateHandlerInterface
{
    /**
     * @var StateMachineInterface
     */
    private $stateMachine;

    /**
     * @param StateMachineInterface $stateMachine
     */
    public function __construct(StateMachineInterface $stateMachine)
    {
        $this->stateMachine = $stateMachine;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(
        EntityInterface $resource,
        RequestConfiguration $configuration,
        ObjectManager $manager
    ): void {
        if ($configuration->hasStateMachine()) {
            $this->stateMachine->apply($configuration, $resource);
        }

        $manager->flush();
    }
}
