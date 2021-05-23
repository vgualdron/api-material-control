<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Models\User;

class ValidateRol
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {        
        $permCall = $request->route()[1]['as'];
        //$endPoint = $request->path();
        //User::permission($endPoint)->get()
        $user = $this->auth->user();
        $permissions = $user->getAllPermissions();        
        $array = array();
        foreach($permissions as $perm){
            $array[] = $perm->name;
        }             
        if (!in_array($permCall, $array)) {
            //print_r($request->route()[1]['uses']);            
            return response('No cuenta con este permiso', 401);
        }
        return $next($request);
    }
}
