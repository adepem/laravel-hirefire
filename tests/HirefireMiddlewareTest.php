<?php

namespace Adepem\HirefireMiddleware\Test;

use Adepem\HirefireMiddleware\HirefireMiddleware;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TiMacDonald\Log\LogFake;

class HirefireMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Log::swap(new LogFake);
    }

    /** * @test */
    public function it_does_not_log_if_not_enabled()
    {
        $passed = false;
        config()->set('hirefire.log_queue_time', false);
        $middleware = new HirefireMiddleware();
        $request = new Request();

        $middleware->handle($request, function () use (&$passed) {
            $passed = true;
        });

        Log::channel('laravel-hirefire')->assertNothingLogged();
        $this->assertTrue($passed);
    }

    /** * @test */
    public function it_does_not_log_if_enabled_but_header_is_not_present()
    {
        $passed = false;
        config()->set('hirefire.log_queue_time', true);
        $middleware = new HirefireMiddleware();
        $request = new Request();

        $middleware->handle($request, function () use (&$passed) {
            $passed = true;
        });

        Log::channel('laravel-hirefire')->assertNothingLogged();
        $this->assertTrue($passed);
    }

    /** * @test */
    public function it_logs_zero_if_enabled_but_header_is_in_the_future()
    {
        $passed = false;
        config()->set('hirefire.log_queue_time', true);
        $middleware = new HirefireMiddleware();
        $request = new Request();
        $request->headers->add([
            'X-Request-Start' => now()->addCentury()->valueOf(),
        ]);

        $middleware->handle($request, function () use (&$passed) {
            $passed = true;
        });

        Log::channel('laravel-hirefire')->assertLoggedMessage('info', '[hirefire:router] queue=0ms');
        $this->assertTrue($passed);
    }

    /** * @test */
    public function it_logs_correct_queue_time_if_enabled_and_header_is_in_the_past()
    {
        $passed = false;
        CarbonImmutable::setTestNow(now()->startOfSecond());

        config()->set('hirefire.log_queue_time', true);
        $middleware = new HirefireMiddleware();
        $request = new Request();
        $request->headers->add([
            'X-Request-Start' => now()->subMillisecond()->valueOf(),
        ]);

        $middleware->handle($request, function () use (&$passed) {
            $passed = true;
        });

        Log::channel('laravel-hirefire')->assertLogged('info', function ($message, $context) {
            return Str::of($message)->match('/[hirefire:router] queue=\d+ms/');
        });
        $this->assertTrue($passed);
    }
}
