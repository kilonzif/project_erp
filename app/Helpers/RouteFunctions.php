<?php
/**
 * Created by PhpStorm.
 * User: Sowee - Makedu
 * Date: 9/5/2018
 * Time: 9:46 PM
 */
use App\Classes\ToastNotification;
use Illuminate\Support\Facades\Route;


/**
 * @param $route
 * @param bool $multilevel
 * @param string $output
 *
 * @return string
 */
function isRouteActive($route, $multilevel = false, $output = ' active')
{
    if ($multilevel){
        return ( str_contains(Route::currentRouteName(), $route)) ? $output : '';
    }

    return ( Route::currentRouteName() == $route) ? $output : '';
}
/**
 * @param $route
 * @param bool $multilevel
 * @param string $outputClass
 *
 * @return string
 */
function isRouteActiveClass($route, $multilevel = false, $outputClass = 'btn-secondary')
{
    if ($multilevel){
        return ( str_contains(Route::currentRouteName(), $route)) ? $outputClass : '';
    }

    return ( Route::currentRouteName() == $route) ? $outputClass : '';
}

/**
 * @return string
 */
function currentRouteName(){
    $route_name = Route::currentRouteName();
    $breadcrumb = str_replace([".", "_"], [" / ", " "], $route_name);
    return $breadcrumb;
}
function notify( ToastNotification $notification){
    session()->push('notifications', $notification);
    return;
}

/**
 *
 */
function flushNotifications(){
    session()->forget('notifications');
}