<?php

namespace App\Http\Middleware;

use App\Service\System\MenuService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use App\Events\SystemLog;
use App\Service\Common\ResponseService;

class Permissions
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

        $userInfo = Auth::user()->toArray();
        $uri = Route::getCurrentRoute()->uri;
        // menu信息
        $menuService = new MenuService();
        $menus = $menuService->getAuthMenus($userInfo['id']);

        view()->share('menus', $menus);
        view()->share('tab', url()->current());

        if (empty(auth()->user())) {
            return ResponseService::jumpError(ResponseService::USER_FAIL);
        }

        if (!empty($userInfo) && $userInfo['is_lock'] === 1) {
            return ResponseService::jumpError(ResponseService::USER_FORBID);
        }
        //Event::fire(new SystemLog($userInfo['name'], $request));

        if (!empty($uri) && !Gate::allows('permission-route', $uri)) {
            if (request()->ajax()) {
                return ResponseService::error('没有权限访问');
            } else {
                //abort(403, '抱歉，你没有权限访问该页面！');
            	return ResponseService::jumpError(ResponseService::USER_UNAUTHORIZED);
            }
        }

        return $next($request);
    }

}
