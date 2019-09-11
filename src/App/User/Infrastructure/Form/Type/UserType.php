<?php

namespace CTIC\App\User\Infrastructure\Form\Type;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Infrastructure\Repository\AccountRepository;
use CTIC\App\Base\Infrastructure\Form\Type\AbstractResourceType;
use CTIC\App\Base\Infrastructure\Doctrine\Form\Type\EntityType;
use CTIC\App\Company\Domain\Company;
use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Permission\Domain\PermissionInterface;
use CTIC\App\User\Domain\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class UserType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('account',  EntityType::class, [
                'label' => 'Cuenta',
                'class' => Account::class,
                'query_builder' => function (AccountRepository $er) {
                    return $er
                        ->createQueryBuilder('a');
                },
                'choice_label' => 'name'
            ])
            ->add('defaultCompany',  EntityType::class, [
                'label' => 'Empresa por defecto',
                'class' => Company::class,
                'query_builder' => function (CompanyRepository $er) {
                    return $er
                        ->createQueryBuilder('a');
                },
                'choice_label' => 'businessName'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nombre',
            ])
            ->add('password', TextType::class, [
                'label' => 'Password nuevo'
            ])
            ->add('permission', ChoiceType::class, [
                'label' => 'Permiso',
                'choices'   => array(
                    'Empleado'          => PermissionInterface::TYPE_EMPLOYEE,
                    'Usuario'           => PermissionInterface::TYPE_USER,
                    'Administrador'     => PermissionInterface::TYPE_ADMINISTRATOR
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
        return 'ctic_app_user';
    }
}
