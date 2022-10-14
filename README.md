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
