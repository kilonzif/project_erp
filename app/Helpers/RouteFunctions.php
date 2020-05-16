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

function milestone_status($id)
{
    switch ($id) {
        case 1:
            $tag = '<div class="badge badge-glow badge-pill badge-warning" style="margin-top: 10px; padding: 7px 15px">Pending</div>';
            break;
        case 2:
            $tag = '<div class="badge badge-glow badge-pill badge-primary" style="margin-top: 10px; padding: 7px 15px">Submitted</div>';
            break;
        case 3:
            $tag = '<div class="badge badge-glow badge-pill badge-success" style="margin-top: 10px; padding: 7px 15px">Approved</div>';
            break;
        case 4:
            $tag = '<div class="badge badge-glow badge-pill badge-danger" style="margin-top: 10px; padding: 7px 15px">Not approved</div>';
            break;
        default:
            $tag = '';
    }
    return $tag;
}
function lang($word,$language=null)
{
    $select_language = new \App\Classes\CommonFunctions();
    $lang = $select_language->webFormLang($language);
    if ($language == null || $lang == "") {
        return $word;
    }
    elseif (array_key_exists($word,$lang)) {
        return $lang[$word];
    } else {
        return $word;
    }
}