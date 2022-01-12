<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
//use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//$router->post('/users', ['uses' => 'UserController@index']);
/*$router->get('/login/{id}', ['prefix' => 'auth', function (Request $request, $id) {

    $user = Auth::user();

    $user = $request->user();
    print_r($user) ;
    //
}]);*/
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');
});
