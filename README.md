[![Continuous Integration on Pull Request](https://github.com/MartijnVooges/common-tools/actions/workflows/ci.yml/badge.svg)](https://github.com/MartijnVooges/common-tools/actions/workflows/ci.yml)

# Api Utilities
This repository contains multiple utilities which are commonly used in the API/service, easiliy available for all projects.

- [Installation](#installation)
- [Access Layers explained](#access-layers) — The differences between `User` or `Admin`, etc
- [Unittest Helper](docs/usage-unittest-helper.md) — Makes Unittesting a lot lesss boilerplate
- [ApiPlatform](docs/usage-unittest-helper.md)
  -  [ContextGroupBuilder](docs/usage-context-group-builder.md) — Serializer Groups and Access Layer determination
- [InternalHttpClient](docs/usage-http-client.md) — How the microservices communicate with eachother

### Installation
- Add repository to composer
  ```yaml
    "repositories": [..., {"type": "vcs", "url": "git@github.com:MartijnVooges/common-tools.git"}],
  ```
- Add package
  ```bash
  composer require martijnvooges/common-tools
  ```
- Add ENV values:
  ```yaml
   ###> JWT ###
  PROJECT_SLUG=example-project
  INTERNAL_HTTP_CLIENT_CLIENT_ID="some-id"
  INTERNAL_HTTP_CLIENT_CLIENT_SECRET="some-very-long-nice-secret"
  ###< JWT ###
  ```

### Access Layers

Not everyone can access everything. This matrix shows who can access what.
We have three 'layers' of access:

| Layer          | Description                                                                                           |
|----------------|-------------------------------------------------------------------------------------------------------|
| **PUBLIC**     | No User of any kind is provided. Minimal data (if any) should be send                                 |
| **USER**       | The paying customer wanting to send packages. This is the customer of the Company.                    |
| **ADMIN**      | The admin who can use the /admin dashboard and see eg the orders of their USERs. This is the Company. |
| **UBER_ADMIN** | The highest level, the one who can configure ADMINs and high risk settings.                           |

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
## * ContextGroupBuilder [(docs)](docs/usage-context-group-builder.md)
Depending on who is requesting the data (eg an USER or ADMIN or UBER_ADMIN) and what actions they're doing (eg READ, UPDATE, etc) you might want to show different data.
This feature exists to standardize the way this works.

```php
#[Get(
    normalizationContext: ['groups' => ['{ACCESS_LEVEL}:{ENTITY}:read']],
)]
```
This gets converted to `ADMIN:EXAMPLE:READ` in case an admin does this request, or `USER:EXAMPLE:READ` if a user does that same exact request.
For configuration at the entity->property leven, please see the  [(docs)](docs/usage-context-group-builder.md).