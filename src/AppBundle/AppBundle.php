<?php

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\FOSUserOverridePass;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AppBundle
 *
 * @package AppBundle
 */
class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FOSUserOverridePass());
    }
}
