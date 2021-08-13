<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Key;

class AuthCheck
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
        $key = Key::select('key')->first();

        if(!isset($key->key) && ($request->path() != '/')) {
            return redirect('/')->with('error','API Key is not set.');
        }

        return $next($request);
    }
}
