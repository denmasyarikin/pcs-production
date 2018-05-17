<?php

$router->get('/', ['as' => 'production.service.list', 'uses' => 'ServiceController@getList']);
$router->get('/type/configuration_type', ['as' => 'production.service.type.configuration_type.list', 'uses' => 'ServiceTypeController@getConfigurationTypeList']);
$router->get('/type/{id}', ['as' => 'production.service.type.detail', 'uses' => 'ServiceTypeController@getDetail']);
$router->get('/{id}', ['as' => 'production.service.detail', 'uses' => 'ServiceController@getDetail']);
$router->get('/{id}/type', ['as' => 'production.service.type.list', 'uses' => 'ServiceTypeController@getList']);
$router->get('/{id}/type/{type_id}/price', ['as' => 'production.service.price.list', 'uses' => 'ServicePriceController@getList']);
$router->get('/{id}/type/{type_id}/configuration', ['as' => 'production.service.configuration.list', 'uses' => 'ConfigurationController@getList']);

$router->group(['middleware' => 'manage:production,service,write'], function ($router) {
    $router->post('/', ['as' => 'production.service.create', 'uses' => 'ServiceController@createService']);
    $router->put('/{id}', ['as' => 'production.service.update', 'uses' => 'ServiceController@updateService']);
    $router->delete('/{id}', ['as' => 'production.service.delete', 'uses' => 'ServiceController@deleteService']);

    $router->group(['prefix' => '/{id}/type'], function ($router) {
        $router->post('/', ['as' => 'production.service.type.create', 'uses' => 'ServiceTypeController@createType']);
        $router->put('/{type_id}', ['as' => 'production.service.type.update', 'uses' => 'ServiceTypeController@updateType']);
        $router->delete('/{type_id}', ['as' => 'production.service.type.delete', 'uses' => 'ServiceTypeController@deleteType']);

        $router->group(['prefix' => '/{type_id}/price'], function ($router) {
            $router->post('/', ['as' => 'production.service.price.create', 'uses' => 'ServicePriceController@createPrice']);
            $router->put('/{price_id}', ['as' => 'production.service.price.update', 'uses' => 'ServicePriceController@updatePrice']);
            $router->delete('{price_id}', ['as' => 'production.service.price.delete', 'uses' => 'ServicePriceController@deletePrice']);
        });

        $router->group(['prefix' => '/{type_id}/configuration'], function ($router) {
            $router->post('/', ['as' => 'production.service.configuration.create', 'uses' => 'ConfigurationController@createConfiguration']);
            $router->put('/{configuration_id}', ['as' => 'production.service.configuration.update', 'uses' => 'ConfigurationController@updateConfiguration']);
            $router->delete('{configuration_id}', ['as' => 'production.service.configuration.delete', 'uses' => 'ConfigurationController@deleteConfiguration']);
        });
    });
});