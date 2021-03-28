<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaxExecutionTime
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int $seconds
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $seconds = 30)
    {
        ini_set('max_execution_time', $seconds);
        set_time_limit ($seconds);
        return $next($request);
    }
}
