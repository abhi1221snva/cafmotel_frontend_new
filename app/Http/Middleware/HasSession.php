<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class HasSession
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = Session::get('tokenId');
        if (empty($token)) {
            if ($request->expectsJson()) {
                return response()->json([
                    "success" => false,
                    "message" => "Session expired",
                    "data" => ["Please relogin"]
                ]);
            } else {
                Session::put(["message" => "Session expired"]);
                return redirect("/");
            }
        }

        return $next($request);
    }
}
