<?php

namespace App\Http\Middleware;

use App\Enums\RoleType;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckRedirectUserRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentRouteName = Route::currentRouteName();
        if (in_array(
            $currentRouteName,
            $this->userAccessRoutes()[auth()->user()->role->name]
        )) {
            return $next($request);
        } else {
            abort(403);
        }
    }

    /**
     * Summary of userAccessRoutes
     * @return array
     */
    public function userAccessRoutes(): array
    {
        $routes = [];
        if (
            (auth()->user()->singleAppLinks->isEmpty()
                && auth()->user()->subLinks->isEmpty())
            && auth()->user()->role->name === RoleType::ADMIN_SCHOOL
        ) {
            $routes = [
                'dashboard.main',
                'admin.main',
                'admin.role',
                'main.schools',
                'navigation.single',
                'navigation.sub',
                'navigation.multi'
            ];
        } else {
            foreach (auth()->user()->singleAppLinks as $singleAppLink) {
                $routes[] = $singleAppLink->route;
            }
            foreach (auth()->user()->subLinks as $subLink) {
                $routes[] = $subLink->route;
            }
        }


        return [
            RoleType::SCHOOL_FINANCE => $routes,
            RoleType::SCHOOL_SECRETARY => $routes,
            RoleType::SCHOOL_MANAGER => $routes,
            RoleType::ADMIN_SCHOOL => $routes,
            RoleType::APP_ADMIN => $routes,
            RoleType::SCHOOL_MONEY_COLLECTOR => $routes,
            RoleType::SCHOOL_TEACHER => $routes,
            RoleType::SCHOOL_DIRECTOR => $routes,
            RoleType::SCHOOL_BOSS => $routes,
            RoleType::SCHOOL_GUARD => $routes,
        ];
    }
}
