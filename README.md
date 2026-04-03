[![Continuous Integration on Pull Request](https://github.com/MartijnVooges/common-tools/actions/workflows/ci.yml/badge.svg)](https://github.com/MartijnVooges/common-tools/actions/workflows/ci.yml)

# Api Utilities
This repository contains multiple utilities which are commonly used in the API/service, easiliy available for all projects.

- [Installation](#installation)
- [Unittest Helper](docs/usage-unittest-helper.md) — Makes Unittesting a lot lesss boilerplate

### Installation
- Add repository to composer
  ```yaml
    "repositories": [..., {"type": "vcs", "url": "git@github.com:MartijnVooges/UnittestHelper.git"}],
  ```
- Add package
  ```bash
  composer require martijnvooges/unittest-helper
  ```
---

## UnittestHelper [(docs)](docs/usage-unittest-helper.md)
Making PHPUnittests easier and less boilerplatey

```php
class ExampleClassTest extends TestCase
{
    private UnittestHelper $helper;
    
    public function __construct() {
        $this->helper = new UnittestHelper($this);
    }
    
    public function testDemo(): void
    {
        $service = $this->helper->build(ExampleClass::class);
    
        // is the equivalent of:

        $service = new ExampleClass(
            $this->createMock(FooService::class),
            $this->createMock(BarService::class),
            $this->createMock(QuxService::class),
        );
    }
}
```
