<?php

namespace CTIC\App\PaymentMethod\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\Controller\ResourceController;
use CTIC\App\PaymentMethod\Domain\PaymentMethod;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use CTIC\App\PaymentMethod\Domain\PaymentMethodExpire;

class PaymentMethodController extends ResourceController
{
    /**
     * @var PaymentMethod|null
     */
    protected $resource;

    /**
     * @var PaymentMethodExpire[]|null
     */
    protected $oldExpires;

    /**
     * @param EntityInterface|PaymentMethod $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeCreateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
    }

    /**
     * @param EntityInterface|PaymentMethod $resource
     * @param RequestConfiguration $configuration
     *
     * @throws
     */
    protected function completeUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
    }

    /**
     * @param EntityInterface|PaymentMethod $resource
     * @param RequestConfiguration $configuration
     */
    protected function prepareEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->oldExpires = array();
        $expires = $resource->getExpires();

        foreach ($expires as $expire) {
            $this->oldExpires[] = $expire;
        }
    }

    /**
     * @param EntityInterface|PaymentMethod $resource
     * @param RequestConfiguration $configuration
     */
    protected function prepareUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->prepareEntity($resource, $configuration);
    }

    /**
     * @param EntityInterface|PaymentMethod $resource
     * @param RequestConfiguration $configuration
     */
    protected function prepareCreateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->prepareEntity($resource, $configuration);
    }

    /**
     * @param EntityInterface|PaymentMethod $resource
     * @param RequestConfiguration $configuration
     *
     * @throws
     */
    protected function postEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $expires = $resource->getExpires();
        $oldExpires = $this->oldExpires;

        /** @var PaymentMethodExpire $expire */
        foreach ($expires as $expire) {
            foreach ($oldExpires as $key => $oldExpire) {
                if ($expire->getId() == $oldExpire->getId()) {
                    unset($oldExpires[$key]);
                }
            }

            $expire->paymentMethod = $resource;

            $this->manager->persist($expire);
        }

        foreach ($oldExpires as $oldExpire) {
            $this->manager->remove($oldExpire);
        }

        $this->manager->flush();
    }

    /**
     * @param EntityInterface|PaymentMethod $resource
     * @param RequestConfiguration $configuration
     */
    protected function postCreateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->postEntity($resource, $configuration);
    }

    /**
     * @param EntityInterface|PaymentMethod $resource
     * @param RequestConfiguration $configuration
     */
    protected function postUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->postEntity($resource, $configuration);
    }
}