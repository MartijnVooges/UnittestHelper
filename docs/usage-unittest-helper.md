# UnittestHelper usage
This package offers the UnittestHelper to make writing test a lot simpler, mainly by reducing the amount of boilerplate, result in more clear code.

## Setup
- Add repository to composer
  ```yaml
    "repositories": [..., {"type": "vcs", "url": "git@github.com:MartijnVooges/common-tool.git"}],
  ```
- Add package
  ```bash
  composer require martijnvooges/common-tools
  ```


## Configuration
No configuration is required.

## Implementation
### UnittestHelper
The main task of the helper is to reduce repetative constructors. In the builder you only need to add a testcase and class you want a real service with mocked parameters.
If you want to add a custom mock, you only need to pass that to the builder

```php
// The follow example will be about this class:
class ExampleClass
{
    public function __construct(
        private FooService $fooService,
        private BarService $barService,
        private BazService $bazService,
    ) {
    }
}

class ExampleClassTest extends TestCase
{
    private UnittestHelper $helper;
    
    public function __construct() {
        $this->helper = new UnittestHelper($this);
    }
}
```
 
```php
public function testDemo(): void
{
    $service = $this->helper->build(ExampleClass::class);
    // is the equivelant of:
    $service = new ExampleClass(
        $this->createMock(FooService::class),
        $this->createMock(BarService::class),
        $this->createMock(BazService::class),
        $this->createMock(QuxService::class),
    );
}
```
```php
public function testDemo(): void
{
    $barServiceMock = $this->createMock(BarService::class),
    $barServiceMock->expects(self::never())->method('someMethod');
    // ---
    
    $service = $this->helper->build(ExampleClass::class, $barServiceMock);
    // is the equivelant of:
    $service = new ExampleClass(
        $this->createMock(FooService::class),
        $barServiceMock,
        $this->createMock(BazService::class),
        $this->createMock(QuxService::class),
    );
}
```

