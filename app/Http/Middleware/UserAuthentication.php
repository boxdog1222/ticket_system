<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Controller;
use Closure;
use Auth;
use App;

class UserAuthentication extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if(Auth::check()) {
            
            $user = Auth::user();
            // echo '<pre>';
            // var_dump($user);
            // echo '</pre>';
            
            if($user -> status != 1) {
                Auth::logout();
                return redirect('/logout');
            } else {
                $_SESSION['user_name'] = $user -> name;
            }
            
            // if($_SERVER['REQUEST_URI'] == '/account' && $user -> authority == 0) {
            //     return redirect('/backend');
            // }

            return $next($request); //有登入回傳頁面
        } else {
            return redirect('/');
        }

    }
}
