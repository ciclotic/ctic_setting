<?php
namespace CTIC\App\Permission\Domain\Fixture;

use CTIC\App\Permission\Application\CreatePermission;
use CTIC\App\Permission\Domain\Command\PermissionCommand;
use CTIC\App\Permission\Domain\Permission;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class PermissionFixture extends AbstractFixture
{
    /**
     * @param string $route
     * @param int $type
     * @param ObjectManager $manager
     *
     * @return void
     *
     * @throws
     */
    protected function persistPermission($route, $type, ObjectManager $manager) : void
    {
        $permissionCommand = new PermissionCommand();
        $permissionCommand->route = $route;
        $permissionCommand->type = $type;
        $permission = CreatePermission::create($permissionCommand);
        $manager->persist($permission);

        $manager->flush();
    }

    /**
     * @param string $base
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function loadGroup($base, $manager)
    {
        $this->persistPermission('GET_' . $base . '_listado', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('GET_' . $base . '_listado', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('GET_' . $base . '_mostrar', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('GET_' . $base . '_crear', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('GET_' . $base . '_modificar', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('POST_' . $base . '_eliminar', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('POST_' . $base . '_eliminargrupo', Permission::TYPE_ADMINISTRATOR, $manager);
    }

    public function load(ObjectManager $manager)
    {
        $this->persistPermission('dashboard', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('/login', Permission::TYPE_ANONYMOUS, $manager);
        $this->persistPermission('login', Permission::TYPE_ANONYMOUS, $manager);
        $this->persistPermission('/logout', Permission::TYPE_ANONYMOUS, $manager);
        $this->persistPermission('logout', Permission::TYPE_ANONYMOUS, $manager);
        $this->persistPermission('/fichar', Permission::TYPE_EMPLOYEE, $manager);
        $this->persistPermission('fichar', Permission::TYPE_EMPLOYEE, $manager);
        $this->persistPermission('GET_fichar', Permission::TYPE_EMPLOYEE, $manager);
        $this->persistPermission('POST_fichar', Permission::TYPE_EMPLOYEE, $manager);
        $this->persistPermission('cambiar_empresa', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('GET_sistema_modificar', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('GET_fichar_listado_trabajando', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('informes', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('GET_trabajadores_available', Permission::TYPE_ADMINISTRATOR, $manager);
        $this->persistPermission('GET_calendario', Permission::TYPE_ADMINISTRATOR, $manager);

        $this->loadGroup('usuarios', $manager);
        $this->loadGroup('empresas', $manager);
        $this->loadGroup('iva', $manager);
        $this->loadGroup('irpf', $manager);
        $this->loadGroup('tarifas', $manager);
        $this->loadGroup('zonas', $manager);
        $this->loadGroup('procedencias', $manager);
        $this->loadGroup('bancos', $manager);
        $this->loadGroup('metodospago', $manager);
        $this->loadGroup('almacenes', $manager);
        $this->loadGroup('clientes', $manager);
        $this->loadGroup('dispositivos', $manager);
        $this->loadGroup('fichar', $manager);
        $this->loadGroup('eventos', $manager);
        $this->loadGroup('trabajadores', $manager);
        $this->loadGroup('trabajadores_categoria', $manager);
        $this->loadGroup('trabajadores_area', $manager);
        $this->loadGroup('trabajadores_baja', $manager);
        $this->loadGroup('trabajadores_asuntos_personales', $manager);
    }
}