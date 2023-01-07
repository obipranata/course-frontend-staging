<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\HttpClient;
use Illuminate\Support\Facades\Session;

class Ortu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has("token")) {
            $auth = HttpClient::fetch(
                "GET",
                HttpClient::apiUrl()."me"
            );
            if (!$auth) {
                return redirect("/unauthorized");
            }

            if ($auth['data']['role'] != 2) {
                return redirect("/unauthorized");
            }
        } else {
            return redirect("/unauthorized");
        }
        return $next($request);
    }
}
