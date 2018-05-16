<?php


$router->get('/', ['as' => 'production.service.list', 'uses' => 'ServiceController@getList']);
$router->get('/{id}', ['as' => 'production.service.detail', 'uses' => 'ServiceController@getDetail']);

$router->group(['middleware' => 'manage:production,service,write'], function ($router) {
    $router->post('/', ['as' => 'production.service.create', 'uses' => 'ServiceController@createService']);
    $router->put('/{id}', ['as' => 'production.service.update', 'uses' => 'ServiceController@updateService']);
    $router->delete('/{id}', ['as' => 'production.service.delete', 'uses' => 'ServiceController@deleteService']);
});
