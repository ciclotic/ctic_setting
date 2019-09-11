<?php

namespace CTIC\App\System\Infrastructure\Form\Type;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Infrastructure\Repository\AccountRepository;
use CTIC\App\Base\Infrastructure\Form\Type\AbstractResourceType;
use CTIC\App\Base\Infrastructure\Doctrine\Form\Type\EntityType;
use CTIC\App\Permission\Domain\PermissionInterface;
use CTIC\App\System\Domain\System;
use CTIC\App\System\Domain\SystemInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class SystemType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*public function getName(): string;
        public function isEnabled(): bool;
        public function getStyleTPV(): int;
        public function setStyleTPV($styleTPV): bool;
        public function isEnabledTPVSound(): bool;
        public function getNumberTPVFamilyLines(): int;
        public function getNumberTPVProductLines(): int;
        public function isEnabledTPVAutomaticComplements(): bool;
        public function getTypeTPVPrint(): int;
        public function setTypeTPVPrint($typeTPVPrint): bool;
        public function getTypeClientName(): int;
        public function setTypeClientName($typeClientName): bool;*/
        $builder
            ->setMethod('POST')
            ->add('name', TextType::class, [
                'label' => 'Nombre',
            ])
            ->add('styleTPV', ChoiceType::class, [
                'label' => 'Estilo de la TPV',
                'choices'   => array(
                    'Por defecto'          => System::STYLE_TPV_DEFAULT
                )
            ])
            ->add('enabledTPVSound', ChoiceType::class, [
                'label'     => 'Sonido de la TPV habilitado',
                'choices'   => array(
                    'Si'    => 1,
                    'No'    => 0
                )
            ])
            ->add('enabledRoundDiscount', ChoiceType::class, [
                'label'     => 'Descuento redondeado habilitado',
                'choices'   => array(
                    'Si'    => 1,
                    'No'    => 0
                )
            ])
            ->add('numberTPVFamilyLines', NumberType::class, [
                'label' => 'Número de líneas en las familias de la TPV',
            ])
            ->add('numberTPVProductLines', NumberType::class, [
                'label' => 'Número de líneas en los productos de la TPV',
            ])
            ->add('enabledTPVAutomaticComplements', ChoiceType::class, [
                'label'     => 'Complementos automáticos de la TPV habilitado',
                'choices'   => array(
                    'Si'    => 1,
                    'No'    => 0
                )
            ])
            ->add('typeTPVPrint', ChoiceType::class, [
                'label' => 'Tipo de impresión de la TPV',
                'choices'   => array(
                    'No imprimir - No abrir cajón'  => System::TYPE_TPV_PRINT_NPRINT_NCASH,
                    'Imprimir - Abrir cajón'        => System::TYPE_TPV_PRINT_PRINT_CASH,
                    'No Imprimir - Abrir cajón'     => System::TYPE_TPV_PRINT_NPRINT_CASH,
                    'Imprimir - No abrir cajón'     => System::TYPE_TPV_PRINT_PRINT_NCASH
                )
            ])
            ->add('typeClientName', ChoiceType::class, [
                'label' => 'Tipo de nombre de cliente',
                'choices'   => array(
                    'Nombre fiscal'     => System::TYPE_TPV_CLIENT_NAME_TAX,
                    'Nombre comercial'  => System::TYPE_TPV_CLIENT_NAME_BUSINESS
                )
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'ctic_app_system';
    }
}
