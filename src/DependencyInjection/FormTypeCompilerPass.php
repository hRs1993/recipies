<?php


namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormTypeCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $formTypeServices = $container->findTaggedServiceIds('form.type');
        if (!$formTypeServices) {
            return;
        }

        $taggedServices = $container->findTaggedServiceIds('form.data_transformer');
        if (!$taggedServices) {
            return;
        }

        $tagsForServices = [];
        foreach ($taggedServices as $id => $parameters) {
            foreach ($parameters as $parameter) {
                $tagsForServices[$parameter['class']][] = [
                    'field' => $parameter['field'],
                    'service' => $id
                ];
            }
        }

        foreach ($tagsForServices as $serviceId => $tags)
        {
            if (!count($tags) || !array_key_exists($serviceId, $formTypeServices)) {
                continue;
            }

            $serviceDefinition = $container->findDefinition($serviceId);
            if (!$serviceDefinition) continue;

            foreach ($tags as $parameter) {
                $serviceDefinition->addMethodCall(
                    'addDataTransformer', [
                        $parameter['field'],
                        new Reference($parameter['service'])
                    ]
                );
            }
        }
    }
}