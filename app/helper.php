<?php


function route_class(){
    return str_replace('.','-',\Illuminate\Support\Facades\Route::currentRouteName());
}


function  ngrok_url($routeName ,$parameters = []){

        // 开发环境，并且配置了 NGROK_URL
    if(app()->environment('local') && $url = config('app.ngrok_url')) {

            return $url.route($routeName, $parameters, false);
        }
        return route($routeName, $parameters);
}
