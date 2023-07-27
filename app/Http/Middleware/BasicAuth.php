<?php
/**
 * Basic auth middleware
 */
namespace App\Http\Middleware;

use Closure;

/**
 * Basic Authorization Using Username password
 */
class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $meta = ['status' => false,'message' => 'Unauthorized','message_code' => 'UNAUTHORIZED','status_code' => 401];
        $envAuthUser =  "cafmotel";
        $envAuthPassword =  "%Cafmotel&*%";
        $authUser = isset($_SERVER['PHP_AUTH_USER']) && is_string($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:"";
        $authPassword = isset($_SERVER['PHP_AUTH_PW']) && is_string($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:"";
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $hasCredentails = !(empty($authUser) && empty($authPassword));
        $is_not_authenticated = (
            !$hasCredentails ||
            $authUser != $envAuthUser ||
            $authPassword   != $envAuthPassword
        );
        if ($is_not_authenticated) {
            return response()->json(['meta' => $meta], 401);
        }
        return $next($request);
    }
}
