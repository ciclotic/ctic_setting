<?php

namespace CTIC\App\PaymentMethod\Infrastructure\Form\Type;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Base\Infrastructure\Form\Type\AbstractResourceType;
use CTIC\App\Base\Infrastructure\Doctrine\Form\Type\EntityType;
use CTIC\App\Permission\Domain\PermissionInterface;
use CTIC\App\PaymentMethod\Domain\PaymentMethodInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class PaymentMethodType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('company',  EntityType::class, [
                'label' => 'Empresa',
                'class' => Company::class,
                'query_builder' => function (CompanyRepository $er) {
                    return $er
                        ->createQueryBuilder('a');
                },
                'choice_label' => 'taxName'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nombre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
            ])
            ->add('price', TextType::class, [
                'label' => 'Precio'
            ])
            ->add('percentage', TextType::class, [
                'label' => 'Porcentaje'
            ])
            ->add('enabled', ChoiceType::class, [
                'label'     => 'Habilitado',
                'choices'   => array(
                    'Si'    => 1,
                    'No'    => 0
                )
            ])
            ->add('expires', CollectionType::class, [
                'label'                 => 'Vencimientos',
                'entry_type'            => PaymentMethodExpireType::class,
                'prototype'             => true,
                'allow_add'             => true,
                'allow_delete'          => true
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'ctic_app_paymentMethod';
    }
}
