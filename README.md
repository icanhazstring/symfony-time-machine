# symfony-time-machine
Symfony bundle that adds the capability to change the datetime of an application

## Motivation
Writing tests for your application is somewhat nice and easy. But what about
testing your application that heavily is based upon `DateTime` objects?

There is `symfony/clock` which already provides a `ClockInterface` which you can
use to inject a `NativeClock` or `MockClock` into your services.

But that only works for tests. What if you want to test end-to-end?
You could change the system time of you server, true, but there is an easier way using
this `symfony-time-machine`.

## Installation

```
composer require icanhazstring/symfony-time-machine
```

If the autoregister doesn't work, add the bundle into your `config/bundles.php`.

```php
<?php

return [
    Icanhazstring\SymfonyTimeMachine\TimeMachineBundle => ['all' => true],
];
```

## How it works
This bundle relies on the presence of `ClockInterface` as a service in your application.
Therefor you need to have something like this in your `services.yaml`:

```yaml
services:
  Symfony\Component\Clock\ClockInterface:
    class: Symfony\Component\Clock\NativeClock
```

This bundle will set this service to `public: true` as this is needed to change the
service on request.

Now everytime you boot the `TimeKernel`, it will replace the `ClockInterface` with a `MockClock`
with a parsed datetime string from either a request query/cookie `time-machine` or environment
variable `TIME_MACHINE`.

## Usage
### Web usage
For web usage you have to alter your `public/index.php` a little.

```php
<?php

use App\Kernel;
use Icanhazstring\SymfonyTimeMachine\TimeKernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    (new TimeKernel($kernel))->fromContext($context)->boot();

    return $kerneL;
};
```

### Console usage
For console usage alter the `bin/console`.

```php
return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    (new TimeKernel($kernel))->fromContext($context)->boot();

    return new Application($kernel);
};
```

### Register additional `TimeMachineHandler`
Sometimes you need to alter some other services in your application along with the time-machine.
For this you can implement services implementing the `TimeMachineHandler` interface.

These will be called right after the `ClockInterface` was changed in the container.
The `TimeKernel` will use the `HandlerRegistry` to call `TimeMachineHandler::handle()` on all
available services.

For example, you could add the `time-machine` query parameter to all openapi paths
in your application, as shown here using api-platform:

```php
final class PathModifier implements OpenApiPathModifier, TimeMachineHandler
{
    public function __construct(private readonly OpenApiFactory $openApiFactory)
    {
    }

    public function handles(string $path, PathItem $pathItem): bool
    {
        return true;
    }

    public function modify(PathItem $pathItem): PathItem
    {
        /** @var array<string, null|Operation> $operations */
        $operations = [
            'get' => $pathItem->getGet(),
            'post' => $pathItem->getPost(),
        ];

        foreach ($operations as $method => $operation) {
            if ($operation === null) {
                continue;
            }

            $queryParameters = $operation->getParameters();
            $queryParameters[] = new Parameter(
                name: 'time-machine',
                in: 'query',
                description: 'Sets the applications datetime to a specific value.',
                required: false,
                schema: ['type' => 'string', 'default' => 'now']
            );

            $wither = 'with'.ucfirst($method);
            /** @var PathItem $pathItem */
            $pathItem = $pathItem->{$wither}($operation->withParameters($queryParameters));
        }

        return $pathItem;
    }

    public function handle(): void
    {
        $this->openApiFactory->addModifier($this);
    }
}
```
