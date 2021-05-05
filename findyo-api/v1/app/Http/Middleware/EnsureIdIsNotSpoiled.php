<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Hashids\Hashids;
use Illuminate\Support\Facades\Route;

class EnsureIdIsNotSpoiled
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
        //Get route controller name
        $controllerName = explode("Controller", class_basename(Route::current()->controller))[0];
        $hashids = new Hashids($controllerName, 15);
        if ($request['cid'] !== $hashids->encode((int)$request['id'])) {
            return response()->json("Bad Request", 400);
        }

        return $next($request);
    }
}
