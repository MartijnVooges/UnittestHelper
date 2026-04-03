<?php

namespace MartijnVooges\Unittest\Tests;

use MartijnVooges\Unittest\VisibilityHelper;
use PHPUnit\Framework\TestCase;

class VisibilityHelperTest extends TestCase
{
    private VisibilityHelper $helper;

    protected function setUp(): void
    {
        $this->helper = new VisibilityHelper();
    }

    public function testSetAndGetPrivateProperty(): void
    {
        $object = new class {
            private string $value = 'initial';
        };

        $this->helper->setInaccessibleProperty($object, 'value', 'changed');

        $result = $this->helper->getInaccessibleProperty($object, 'value');

        $this->assertSame('changed', $result);
    }

    public function testSetProtectedProperty(): void
    {
        $object = new class {
            protected int $number = 1;
        };

        $this->helper->setInaccessibleProperty($object, 'number', 42);

        $result = $this->helper->getInaccessibleProperty($object, 'number');

        $this->assertSame(42, $result);
    }

    public function testSetNonExistingPropertyThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Can't change property 'nonExisting'");

        $object = new class {
            private string $value = 'test';
        };

        $this->helper->setInaccessibleProperty($object, 'nonExisting', 'value');
    }

    public function testGetPrivateProperty(): void
    {
        $object = new class {
            private string $secret = 'hidden';
        };

        $result = $this->helper->getInaccessibleProperty($object, 'secret');

        $this->assertSame('hidden', $result);
    }

    public function testInvokePrivateMethod(): void
    {
        $object = new class {
            private function multiply(int $a, int $b): int
            {
                return $a * $b;
            }
        };

        $result = $this->helper->invokeInaccessibleMethod($object, 'multiply', [2, 3]);

        $this->assertSame(6, $result);
    }

    public function testInvokeProtectedMethod(): void
    {
        $object = new class {
            protected function greet(string $name): string
            {
                return "Hello $name";
            }
        };

        $result = $this->helper->invokeInaccessibleMethod($object, 'greet', ['World']);

        $this->assertSame('Hello World', $result);
    }

    public function testInvokeMethodWithNoParameters(): void
    {
        $object = new class {
            private function getValue(): string
            {
                return 'ok';
            }
        };

        $result = $this->helper->invokeInaccessibleMethod($object, 'getValue');

        $this->assertSame('ok', $result);
    }
}
