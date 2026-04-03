<?php

namespace App\Tests\Unit\TestFiles;

class ClassWithDependencies
{
    public function __construct(
        public DependencyA $depA,
        public DependencyB $depB
    ) {}
}
