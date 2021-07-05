<?php

namespace Adepem\HirefireMiddleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class HirefireMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! config('hirefire.log_queue_time')) {
            return $next($request);
        }

        $requestStart = $request->header('X-Request-Start');

        if (is_null($requestStart)) {
            return $next($request);
        }

        Log::channel('laravel-hirefire')->info('[hirefire:router] queue=' . $this->queueTime($requestStart) . 'ms');

        return $next($request);
    }

    /**
     * @param $requestStart
     * @return int
     */
    private function queueTime($requestStart): int
    {
        $milliseconds = Carbon::createFromTimestampMsUTC($requestStart)->diffInRealMilliseconds(Date::now(), false);

        return $milliseconds < 0 ? 0 : $milliseconds;
    }
}
