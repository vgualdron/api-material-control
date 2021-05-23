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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(["prefix" => "/v1", "middleware" => ["cors", "auth"]], function () use($router){
    $router->group(["prefix" => "/user"], function () use($router){
        $router->post('/register', ['as' => 'user.insert', 'uses' => 'userController@insertUser']);
        $router->get('/list', ['as' => 'user.list', 'uses' => 'userController@listUser']);
        $router->put("/{id}", ['as' => 'user.update', 'uses' => 'userController@updateUser']);
        //$router->put("/{id}", ['as' => 'user.update', 'uses' => 'userController@updateUser']);
        $router->delete("/{id}", ['as' => 'user.delete', 'uses' => 'userController@deleteUser']);
    });

    $router->group(["prefix" => "/thirdFB"], function () use($router){
        $router->get('/list', 'thirdFBController@list');
    });

    $router->group(["prefix" => "/permission"], function () use($router){
        $router->post('/listBySession', 'permissionController@listBySession');
        $router->get('/listBySessionGroup', 'permissionController@listBySessionGroup');
    });
});
