<?php

namespace MartijnVooges\Unittest;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class UnittestHelper
{
    private BuilderHelper $helper;
    public VisibilityHelper $visibilityHelper;

    public function __construct(
        private TestCase $testCase,
    ) {
        $this->helper = new BuilderHelper();
        $this->visibilityHelper = new VisibilityHelper();
    }

    /**
     * Generic Service/Manager builder.
     * - (required) The class we're going to Mock.
     *      - The constructor will be read automatically and mocks will be created for each parameter.
     * - (optional) Provide CUSTOM mocks you want to overwrite.
     *      - The order does not matter.
     *      - All default mocks don't have to be provided, will be done automatically.
     *
     *  It's recommended to find a use case for this method as example.
     *
     * @param class-string $class
     * @param MockObject ...$userProvidedMocks
     *
     * @throws \ReflectionException
     */
    public function build(string $class, MockObject ...$userProvidedMocks): mixed
    {
        $reflection = new \ReflectionClass($class);
        $reflectionConstruct = $reflection->getConstructor();
        if (null === $reflectionConstruct) {
            return new $class();
        }

        $standardMocks = $this->helper->getDefaultMocksForConstruct(
            $this->testCase,
            $reflectionConstruct,
            array_values($userProvidedMocks)
        );
        $mocks = $this->helper->applyUserProvidedMocks(
            $standardMocks,
            array_values($userProvidedMocks)
        );

        return new $class(...array_values($mocks));
    }
}
