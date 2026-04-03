<?php

namespace MartijnVooges\Unittest\Tests;

use App\Tests\Unit\TestFiles\ClassWithDependencies;
use App\Tests\Unit\TestFiles\DependencyA;
use App\Tests\Unit\TestFiles\DependencyB;
use App\Tests\Unit\TestFiles\NoConstructorClass;
use MartijnVooges\Unittest\UnittestHelper;
use PHPUnit\Framework\TestCase;

class UnittestHelperTest extends TestCase
{
    private UnittestHelper $helper;

    protected function setUp(): void
    {
        $this->helper = new UnittestHelper($this);
    }

    public function testBuildWithoutConstructor(): void
    {
        $object = $this->helper->build(NoConstructorClass::class);

        $this->assertInstanceOf(NoConstructorClass::class, $object);
    }

    public function testBuildWithConstructorCreatesMocks(): void
    {
        $object = $this->helper->build(ClassWithDependencies::class);

        $this->assertInstanceOf(ClassWithDependencies::class, $object);
        $this->assertInstanceOf(DependencyA::class, $object->depA);
        $this->assertInstanceOf(DependencyB::class, $object->depB);
    }

    public function testUserProvidedMockOverridesDefault(): void
    {
        $customMock = $this->createMock(DependencyA::class);

        $object = $this->helper->build(
            ClassWithDependencies::class,
            $customMock
        );

        $this->assertSame($customMock, $object->depA);
        $this->assertInstanceOf(DependencyB::class, $object->depB);
    }

    public function testMultipleUserProvidedMocks(): void
    {
        $mockA = $this->createMock(DependencyA::class);
        $mockB = $this->createMock(DependencyB::class);

        $object = $this->helper->build(
            ClassWithDependencies::class,
            $mockB,
            $mockA // order should not matter
        );

        $this->assertSame($mockA, $object->depA);
        $this->assertSame($mockB, $object->depB);
    }
}
