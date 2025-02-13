<?php

declare(strict_types=1);

namespace App\Laravue\Services;

class EntityMapper
{
    public function map(object $convertedInstance, string $targetClass): object
    {
        $reflectionTargetClass = new \ReflectionClass($targetClass);
        $targetInstance = null;
        $targetConstructor = $reflectionTargetClass->getConstructor();
        if (null === $targetConstructor) {
            $targetInstance = new $targetClass();
        } elseif (
            !$targetConstructor->isAbstract()
            && !$targetConstructor->isPrivate()
            && !$targetConstructor->isProtected()
        ) {
            if (
                $targetConstructor->getNumberOfParameters() === 0
                || (
                    $targetConstructor->getNumberOfParameters() === 1
                    && $targetConstructor->getParameters()[0]->getName() === 'argument'
                )
            ) {
                $targetInstance = new $targetClass();
            } else {
                $args = \array_map(
                    fn($param) => $convertedInstance->{$param->name},
                    $targetConstructor->getParameters()
                );

                return new $targetClass(...$args);
            }
        }

        if (null === $targetInstance)
        {
            throw new \Exception(
                \sprintf(
                    'Can not map `%s` to `%s`',
                    \get_class($convertedInstance),
                    $targetClass
                )
            );
        }

        $reflectionInstance = new \ReflectionObject($convertedInstance);
        /** @var \ReflectionProperty $property */
        foreach (
            $reflectionInstance->getProperties(\ReflectionProperty::IS_PUBLIC)
            as $property
        ) {
            $this->setPropertyToInstance(
                $property->name,
                $convertedInstance->{$property->name},
                $targetInstance,
                $reflectionTargetClass
            );
        }
        /** @var $method \ReflectionMethod */
        foreach (
            $reflectionInstance->getMethods(\ReflectionMethod::IS_PUBLIC)
            as $method
        ) {
            if (\substr($method->name, 0, 3) !== 'get') continue;
            $this->setPropertyToInstance(
                \lcfirst(\substr($method->name, 3)),
                $convertedInstance->{$method->name}(),
                $targetInstance,
                $reflectionTargetClass
            );
        }

        return $targetInstance;
    }

    public function instantiateByMap(object $convertedInstance, array $map, string $targetClass): object
    {
        $mappedInstance = new \StdClass();
        foreach ($map as $instanceFieldName => $targetFieldName) {
            $mappedInstance->{$targetFieldName} = $convertedInstance->{$instanceFieldName};
        }

        return $this->map($mappedInstance, $targetClass);
    }

    public function instantiateFromArray(array $entityHash, string $targetEntityClass, array $nested=[]): object
    {
        $stdObject = $this->arrayToStdObject($entityHash);

        foreach ($nested as $fieldName => $nestedClass) {
            if (!\array_key_exists($fieldName, $entityHash)) {
                continue;
            }
            $stdObject->{$fieldName} = $this->instantiateFromArray($entityHash[$fieldName], $nestedClass);
        }

        return $this->map(
            $stdObject,
            $targetEntityClass
        );
    }

    private function arrayToStdObject(array $hash): \StdClass
    {
        return \json_decode(\json_encode($hash));
    }

    private function setPropertyToInstance(
        string $propertyName,
        $value,
        $instance,
        \ReflectionClass $instanceClass
    ): void {
        if (
            $instanceClass->hasProperty($propertyName)
            && $instanceClass->getProperty($propertyName)->isPublic()
        ) {
            $instance->{$propertyName} = $value;
        } elseif (
            $instanceClass->hasMethod('set' . \ucfirst($propertyName))
            && $instanceClass->getMethod('set' . \ucfirst($propertyName))->isPublic()
        ) {
            $instance->{'set' . \ucfirst($propertyName)}($value);
        }
    }
}
