<?php

namespace MartijnVooges\Unittest;

final class VisibilityHelper
{
    public function setInaccessibleProperty(object $object, string $property, mixed $value): void
    {
        try {
            $reflection_property = new \ReflectionProperty($object, $property);
            $reflection_property->setAccessible(true);
            $reflection_property->setValue($object, $value);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException(sprintf("Can't change property '%s'", $property), 0, $e);
        }
    }

    public function getInaccessibleProperty(object $object, string $property): mixed
    {
        $reflection_property = new \ReflectionProperty($object, $property);
        $reflection_property->setAccessible(true);

        return $reflection_property->getValue($object);
    }

    /**
     * @deprecated Don't use lightly, test protected/private methods via a public functions. Use as exception
     *
     * @param array<int, mixed> $parameters
     * @throws \ReflectionException
     */
    public function invokeInaccessibleMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $method = new \ReflectionMethod(get_class($object), $methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
