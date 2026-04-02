<?php

namespace MartijnVooges\Unittest;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BuilderHelper
{
    /**
     * @param array<int, MockObject> $userProvidedMocks
     * @return array<string, MockObject|mixed>
     * @throws \Exception
     */
    public function getDefaultMocksForConstruct(
        TestCase $testCase,
        \ReflectionMethod $reflectionConstruct,
        array $userProvidedMocks
    ): array {
        $mocks = [];
        $reflectionParameters = $reflectionConstruct->getParameters();
        foreach ($reflectionParameters as $reflectionParameter) {
            if (empty($userProvidedMocks) && $reflectionParameter->isOptional()) {
                break;
            }

            /** @var \ReflectionNamedType $type */
            $type = $reflectionParameter->getType();
            $name = $type->getName();
            if (!class_exists($name) && !interface_exists($name)) {
                $mocks[$name . random_int(10000, 99999)] = $this->getNonClassDefaultValue($name, $reflectionParameter);
                continue;
            }

            $mocks[substr($name, strrpos($name, '\\') + 1)] = $this->createMock($testCase, $name);
        }

        return $mocks;
    }

    /**
     * @param array<string, MockObject|mixed> $standardMocks
     * @param array<int|string, MockObject|object> $userProvidedMocks
     *
     * @return array<string, MockObject|mixed>
     *
     * @throws \Exception
     */
    public function applyUserProvidedMocks(array $standardMocks, array $userProvidedMocks): array
    {
        foreach ($userProvidedMocks as $providedMock) {
            $classname = get_class($providedMock);
            $classname = $this->getClassNameToInject($classname);

            if (array_key_exists($classname . 'Interface', $standardMocks)) {
                $classname .= 'Interface';
            }

            $standardMocks[$classname] = $providedMock;
        }

        return $standardMocks;
    }

    /**
     * @param class-string $classname
     */
    private function getClassNameToInject(string $classname): string
    {
        // eg: MockObject_RequestStack_b4b07f3d -> RequestStack
        $classnameParts = explode('_', $classname);
        $classname = $classnameParts[1] ?? $classnameParts[0];

        // Then, eg Symfony\Component\HttpClient\MockHttpClient -> HttpClient
        $classname = explode('\\', $classname);
        $classname = end($classname);
        $classname = str_replace('Mock', '', $classname);

        return $classname;
    }

    private function getNonClassDefaultValue(string $propertyType, \ReflectionParameter $reflectionParameter): mixed
    {
        if ($reflectionParameter->isDefaultValueAvailable()) {
            return $reflectionParameter->getDefaultValue();
        }

        $reflectionType = $reflectionParameter->getType();
        if ($reflectionType) {
            $types = $reflectionType instanceof \ReflectionUnionType ? $reflectionType->getTypes() : [$reflectionType];
            $typeNames = array_map(static fn (\ReflectionNamedType $t) => $t->getName(), $types);
            if (in_array('callable', $typeNames, true)) {
                return static fn () => null;
            }
        }

        return match ($propertyType) {
            'bool' => true,
            'string' => '',
            'int', 'float' => -1,
            'array', 'iterable' => [],
            default => null,
        };
    }

    /**
     * @param class-string $name
     */
    private function createMock(TestCase $testCase, string $name): MockObject
    {
        return new MockBuilder($testCase, $name)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();
    }
}
