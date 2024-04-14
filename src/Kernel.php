<?php

namespace App;

use App\Type\UserRoleType;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Doctrine\DBAL\Types\Type;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {
        parent::boot();

        Type::addType(UserRoleType::NAME, UserRoleType::class);

        $platform = $this->container->get('doctrine.orm.entity_manager')->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', UserRoleType::NAME);
    }
}
