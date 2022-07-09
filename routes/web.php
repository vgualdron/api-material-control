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
    $router->get('/get-active-token', ['as' => 'token.getActiveToken', 'uses' => 'TokenController@getActiveToken']);
});

$router->group(["prefix" => "/v1", "middleware" => ["cors", "auth"]], function () use($router){
    
    $router->group(["prefix" => "/user"], function () use($router){
        $router->post('/', ['as' => 'user.insert', 'uses' => 'UserController@insert']);
        $router->get('/{perPage}/{page}/{text}', ['as' => 'user.list', 'uses' => 'UserController@list']);
        $router->get('/{id}', ['as' => 'user.get', 'uses' => 'UserController@get']);
        $router->put("/{id}", ['as' => 'user.update', 'uses' => 'UserController@update']);
        $router->delete("/{id}", ['as' => 'user.delete', 'uses' => 'UserController@delete']);
        $router->patch("/changePassword/{id}", ['as' => 'user.changePassword', 'uses' => 'UserController@changePassword']);
    });

    $router->group(["prefix" => "/thirdFB"], function () use($router){        
        $router->get('/list', 'ThirdFBController@list');
    });

    $router->group(["prefix" => "/permission"], function () use($router){
        $router->get('/listBySession', 'PermissionController@listBySession');
        $router->get('/listBySessionGroup', 'PermissionController@listBySessionGroup');
        $router->get('/listByGroup', 'PermissionController@listByGroup');
    });   

    $router->group(["prefix" => "/zone"], function () use($router){        
        $router->post('/', ['as' => 'zone.insert', 'uses' => 'ZoneController@insert']);
        $router->get('/{perPage}/{page}/{text}/{zone}', ['as' => 'zone.list', 'uses' => 'ZoneController@list']);
        $router->get('/{id}', ['as' => 'zone.get', 'uses' => 'ZoneController@get']);
        $router->put("/{id}", ['as' => 'zone.update', 'uses' => 'ZoneController@update']);
       $router->delete("/{id}", ['as' => 'zone.delete', 'uses' => 'ZoneController@delete']);
    });

    $router->group(["prefix" => "/yard"], function () use($router){        
        $router->post('/', ['as' => 'yard.insert', 'uses' => 'YardController@insert']);
        $router->get('/{perPage}/{page}/{text}/{yard}/{excludedYard}', ['as' => 'yard.list', 'uses' => 'YardController@list']);
        $router->get('/{id}', ['as' => 'yard.get', 'uses' => 'YardController@get']);
        $router->put("/{id}", ['as' => 'yard.update', 'uses' => 'YardController@update']);
       $router->delete("/{id}", ['as' => 'yard.delete', 'uses' => 'YardController@delete']);
    });

    $router->group(["prefix" => "/material"], function () use($router){        
        $router->post('/', ['as' => 'material.insert', 'uses' => 'MaterialController@insert']);
        $router->get('/{perPage}/{page}/{text}/{material}', ['as' => 'material.list', 'uses' => 'MaterialController@list']);
        $router->get('/{id}', ['as' => 'material.get', 'uses' => 'MaterialController@get']);
        $router->put("/{id}", ['as' => 'material.update', 'uses' => 'MaterialController@update']);
        $router->delete("/{id}", ['as' => 'material.delete', 'uses' => 'MaterialController@delete']);
    });

    $router->group(["prefix" => "/synchronize"], function () use($router){        
        $router->get('/fromServer', ['as' => 'synchronize.fromServer', 'uses' => 'SynchronizeController@fromServer']);
        $router->post('/toServer', ['as' => 'synchronize.toServer', 'uses' => 'SynchronizeController@toServer']);
    });
    
    $router->group(["prefix" => "/role"], function () use($router){        
        $router->post('/', ['as' => 'role.insert', 'uses' => 'RoleController@insert']);
        $router->get('/{perPage}/{page}/{text}', ['as' => 'role.list', 'uses' => 'RoleController@list']);
        $router->get('/{id}', ['as' => 'role.get', 'uses' => 'RoleController@get']);
        $router->put("/{id}", ['as' => 'role.update', 'uses' => 'RoleController@update']);
        $router->delete("/{id}", ['as' => 'role.delete', 'uses' => 'RoleController@delete']);
    });

    $router->group(["prefix" => "/session"], function () use($router){
        $router->get('/', ['as' => 'session.get', 'uses' => 'SessionController@get']);
    });
    
    $router->group(["prefix" => "/tiquet"], function () use($router){
        $router->post('/', ['as' => 'adminTiquet.insert', 'uses' => 'TiquetController@insert']);
        $router->get('/{perPage}/{page}/{text}', ['as' => 'adminTiquet.list', 'uses' => 'TiquetController@list']);
        $router->get('/{id}', ['as' => 'adminTiquet.get', 'uses' => 'TiquetController@get']);
        $router->delete("/{id}", ['as' => 'adminTiquet.delete', 'uses' => 'TiquetController@delete']);
        $router->put("/{id}", ['as' => 'adminTiquet.update', 'uses' => 'TiquetController@update']);
    });
    
    $router->group(["prefix" => "/rate"], function () use($router){        
        $router->post('/', ['as' => 'rate.insert', 'uses' => 'RateController@insert']);
        $router->get('/{perPage}/{page}/{text}', ['as' => 'rate.list', 'uses' => 'RateController@list']);
        $router->get('/{id}', ['as' => 'rate.get', 'uses' => 'RateController@get']);
        $router->put("/{id}", ['as' => 'rate.update', 'uses' => 'RateController@update']);
       $router->delete("/{id}", ['as' => 'rate.delete', 'uses' => 'RateController@delete']);
    });
    
    $router->group(["prefix" => "/third", "middleware" => "cors"], function () use($router){
        $router->get('/{type}/{start_date}/{final_date}', ['uses' => 'ThirdController@listByType']);
    });
    
    $router->group(["prefix" => "/freightSettlement"], function () use($router){
        $router->get('/{start_date}/{final_date}/{conveyor_company}', ['as' => 'freightSettlement.list', 'uses' => 'FreightSettlementController@list']);
        $router->post('/', ['as' => 'freightSettlement.settle', 'uses' => 'FreightSettlementController@settle']);
    });
    
    $router->group(["prefix" => "/materialSettlement"], function () use($router){
        $router->get('/{type}/{start_date}/{final_date}/{third}/{material}/{material_type}', ['as' => 'materialSettlement.list', 'uses' => 'MaterialSettlementController@list']);
        $router->post('/', ['as' => 'materialSettlement.settle', 'uses' => 'MaterialSettlementController@settle']);
    });
    
    $router->group(["prefix" => "/adminFreightSettlement"], function () use($router){
        $router->get('/{perPage}/{page}/{text}/{settlement}', ['as' => 'adminFreightSettlement.list', 'uses' => 'AdminFreightSettlementController@list']);
        $router->get('/{id}', ['as' => 'adminFreightSettlement.get', 'uses' => 'AdminFreightSettlementController@get']);
        $router->put('/{id}', ['as' => 'adminFreightSettlement.update', 'uses' => 'AdminFreightSettlementController@update']);
    }); 

    $router->group(["prefix" => "/adminMaterialSettlement"], function () use($router){
        $router->get('/{perPage}/{page}/{text}/{settlement}', ['as' => 'adminMaterialSettlement.list', 'uses' => 'AdminMaterialSettlementController@list']);
        $router->get('/{id}', ['as' => 'adminMaterialSettlement.get', 'uses' => 'AdminMaterialSettlementController@get']);
        $router->put('/{id}', ['as' => 'adminMaterialSettlement.update', 'uses' => 'AdminMaterialSettlementController@update']);
    });
    
    $router->group(["prefix" => "/adjustment"], function () use($router){        
        $router->post('/', ['as' => 'adjustment.insert', 'uses' => 'AdjustmentController@insert']);
        $router->get('/{perPage}/{page}/{text}/{adjustment}', ['as' => 'adjustment.list', 'uses' => 'AdjustmentController@list']);  
        $router->get('/{id}', ['as' => 'adjustment.get', 'uses' => 'AdjustmentController@get']);
        $router->put("/{id}", ['as' => 'adjustment.update', 'uses' => 'AdjustmentController@update']);
        $router->delete("/{id}", ['as' => 'adjustment.delete', 'uses' => 'AdjustmentController@delete']);
    });
    
    $router->group(["prefix" => "/adjustment"], function () use($router){        
        $router->post('/', ['as' => 'adjustment.insert', 'uses' => 'AdjustmentController@insert']);
        $router->get('/{perPage}/{page}/{text}/{adjustment}', ['as' => 'adjustment.list', 'uses' => 'AdjustmentController@list']);  
        $router->get('/{id}', ['as' => 'adjustment.get', 'uses' => 'AdjustmentController@get']);
        $router->put("/{id}", ['as' => 'adjustment.update', 'uses' => 'AdjustmentController@update']);
        $router->delete("/{id}", ['as' => 'adjustment.delete', 'uses' => 'AdjustmentController@delete']);
    });
    
    $router->group(["prefix" => "/movement"], function () use($router){        
        $router->post('/', ['as' => 'movement.generate', 'uses' => 'MovementController@generate']);
        $router->get('/{start_date}/{final_date}', ['as' => 'movement.get', 'uses' => 'MovementController@get']);
    });
    
    $router->group(["prefix" => "/report"], function () use($router){     
        $router->get('/movementsReport/{movement}/{start_date}/{final_date}/{origin_yard_supplier}/{destiny_yard_customer}/{material}', ['as' => 'report.movementsReport', 'uses' => 'ReportController@movementsReport']);
        $router->get('/yardStockReport/{date}', ['as' => 'report.yardStockReport', 'uses' => 'ReportController@yardStockReport']);
        $router->get('/completeTransfersReport/{start_date}/{final_date}/{origin_yard}/{destiny_yard}', ['as' => 'report.completeTransfersReport', 'uses' => 'ReportController@completeTransfersReport']);
        $router->get('/uncompleteTransfersReport/{start_date}/{final_date}/{origin_yard}/{destiny_yard}', ['as' => 'report.uncompleteTransfersReport', 'uses' => 'ReportController@uncompleteTransfersReport']);
        $router->get('/unbilledPurchasesReport/{start_date}/{final_date}/{supplier}/{material}', ['as' => 'report.unbilledPurchasesReport', 'uses' => 'ReportController@unbilledPurchasesReport']);
        $router->get('/unbilledSalesReport/{start_date}/{final_date}/{customer}/{material}', ['as' => 'report.unbilledSalesReport', 'uses' => 'ReportController@unbilledSalesReport']);
        $router->get('/unbilledFreightReport/{start_date}/{final_date}/{conveyor_company}/{material}', ['as' => 'report.unbilledFreightReport', 'uses' => 'ReportController@unbilledFreightReport']);
    });
});