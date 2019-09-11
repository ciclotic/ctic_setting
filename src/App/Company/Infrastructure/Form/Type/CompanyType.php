<?php

namespace CTIC\App\Company\Infrastructure\Form\Type;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Infrastructure\Repository\AccountRepository;
use CTIC\App\Base\Infrastructure\Form\Type\AbstractResourceType;
use CTIC\App\Base\Infrastructure\Doctrine\Form\Type\EntityType;
use CTIC\App\Permission\Domain\PermissionInterface;
use CTIC\App\Company\Domain\CompanyInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class CompanyType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('taxName', TextType::class, [
                'label' => 'Nombre fiscal',
            ])
            ->add('administratorName', TextType::class, [
                'label' => 'Nombre del administrador',
            ])
            ->add('businessName', TextType::class, [
                'label' => 'Nombre comercial',
            ])
            ->add('taxIdentification', TextType::class, [
                'label' => 'CIF o NIF',
            ])
            ->add('administratorIdentification', TextType::class, [
                'label' => 'DNI del administrador',
            ])
            ->add('ccc', TextType::class, [
                'label' => 'CCC',
            ])
            ->add('address', TextType::class, [
                'label' => 'Dirección',
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Código postal',
            ])
            ->add('town', TextType::class, [
                'label' => 'Ciudad o Población',
            ])
            ->add('country', TextType::class, [
                'label' => 'País',
            ])
            ->add('smtpEmail', TextType::class, [
                'label' => 'Email del SMTP',
            ])
            ->add('smtpHost', TextType::class, [
                'label' => 'Host del SMTP',
            ])
            ->add('smtpPassword', TextType::class, [
                'label' => 'Password del SMTP',
            ])
            ->add('smtpAliasName', TextType::class, [
                'label' => 'Alias del remitente de correos',
            ])
            ->add('includedIVA', ChoiceType::class, [
                'label'     => 'Iva incluído habilitado',
                'choices'   => array(
                    'Si'    => 1,
                    'No'    => 0
                )
            ])
            ->add('defect', ChoiceType::class, [
                'label'     => 'Por defecto',
                'choices'   => array(
                    'Si'    => 1,
                    'No'    => 0
                )
            ])
            ->add('enabled', ChoiceType::class, [
                'label'     => 'Habilitado',
                'choices'   => array(
                    'Si'    => 1,
                    'No'    => 0
                )
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'ctic_app_company';
    }
}
