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

$router->group(["prefix" => "/token", "middleware" => "cors"], function () use($router){    
    $router->get('/get-active-token', ['as' => 'token.getActiveToken', 'uses' => 'tokenController@getActiveToken']);
});

$router->group(["prefix" => "/v1", "middleware" => ["cors", "auth"]], function () use($router){
    
    $router->group(["prefix" => "/user"], function () use($router){
        $router->post('/', ['as' => 'user.insert', 'uses' => 'userController@insert']);
        $router->get('/', ['as' => 'user.list', 'uses' => 'userController@list']);
        $router->get('/{id}', ['as' => 'user.get', 'uses' => 'userController@get']);
        $router->put("/{id}", ['as' => 'user.update', 'uses' => 'userController@update']);
        $router->delete("/{id}", ['as' => 'user.delete', 'uses' => 'userController@delete']);
        $router->patch("/changePassword/{id}", ['as' => 'user.changePassword', 'uses' => 'userController@changePassword']);
    });

    $router->group(["prefix" => "/thirdFB"], function () use($router){
        $router->get('/list', 'thirdFBController@list');
    });

    $router->group(["prefix" => "/permission"], function () use($router){
        $router->post('/listBySession', 'permissionController@listBySession');
        $router->get('/listBySessionGroup', 'permissionController@listBySessionGroup');
    });   

    $router->group(["prefix" => "/zone"], function () use($router){        
        $router->post('/', ['as' => 'zone.insert', 'uses' => 'zoneController@insert']);
        $router->get('/', ['as' => 'zone.list', 'uses' => 'zoneController@list']);
        $router->get('/{id}', ['as' => 'zone.get', 'uses' => 'zoneController@get']);
        $router->put("/{id}", ['as' => 'zone.update', 'uses' => 'zoneController@update']);
       $router->delete("/{id}", ['as' => 'zone.delete', 'uses' => 'zoneController@delete']);
    });

    $router->group(["prefix" => "/yard"], function () use($router){        
        $router->post('/', ['as' => 'yard.insert', 'uses' => 'yardController@insert']);
        $router->get('/', ['as' => 'yard.list', 'uses' => 'yardController@list']);
        $router->get('/{id}', ['as' => 'yard.get', 'uses' => 'yardController@get']);
        $router->put("/{id}", ['as' => 'yard.update', 'uses' => 'yardController@update']);
       $router->delete("/{id}", ['as' => 'yard.delete', 'uses' => 'yardController@delete']);
    });

    $router->group(["prefix" => "/material"], function () use($router){        
        $router->post('/', ['as' => 'material.insert', 'uses' => 'materialController@insert']);
        $router->get('/', ['as' => 'material.list', 'uses' => 'materialController@list']);
        $router->get('/{id}', ['as' => 'material.get', 'uses' => 'materialController@get']);
        $router->put("/{id}", ['as' => 'material.update', 'uses' => 'materialController@update']);
        $router->delete("/{id}", ['as' => 'material.delete', 'uses' => 'materialController@delete']);
    });

    $router->group(["prefix" => "/synchronize"], function () use($router){        
        $router->get('/fromServer', ['as' => 'synchronize.fromServer', 'uses' => 'synchronizeController@fromServer']);
        $router->get('/toServer', ['as' => 'synchronize.toServer', 'uses' => 'materialController@toServer']);
    });
});
