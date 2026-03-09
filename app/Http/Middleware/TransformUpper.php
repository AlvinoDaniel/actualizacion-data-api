<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class TransformUpper
{
    protected $excludeInputs = [
        'foto',
        'file'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $transformedInput = [];

        foreach ($request->request->all() as $key => $value) {
            $transformedInput[$key] = (is_string($value) && !in_array($key, $this->excludeInputs)) ? Str::upper($value) : $value;
        }

        $request->replace($transformedInput);

        return $next($request);
    }
}
