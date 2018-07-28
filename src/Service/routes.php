<?php

$router->get('/', ['as' => 'production.service.list', 'uses' => 'ServiceController@getList']);
$router->get((RE ? 'option' : '0001').'/'.(RE ? 'configuration_type' : '0002'), ['as' => 'production.service.option.configuration_type', 'uses' => 'ServiceOptionController@getConfigurationTypeList']);
$router->get((RE ? 'option' : '0001').'/{id}', ['as' => 'production.service.option.detail', 'uses' => 'ServiceOptionController@getDetail']);
$router->get('/{id}', ['as' => 'production.service.detail', 'uses' => 'ServiceController@getDetail']);
$router->group(['prefix' => '/{id}/'.(RE ? 'option' : '0001')], function ($router) {
    $router->get('', ['as' => 'production.service.option.list', 'uses' => 'ServiceOptionController@getList']);
    $router->get('/{option_id}/price', ['as' => 'production.service.option.price.list', 'uses' => 'ServicePriceController@getList']);
    $router->get('/{option_id}/'.(RE ? 'configuration' : '0004'), ['as' => 'production.service.option.configuration.list', 'uses' => 'ConfigurationController@getList']);
    $router->post('/{option_id}/'.(RE ? 'calculate' : '0005'), ['as' => 'production.service.option.calculate', 'uses' => 'ServiceOptionController@calculatePrice']);
});

$router->group(['middleware' => 'manage:production,service,write'], function ($router) {
    $router->put((RE ? 'sorting' : '0006'), ['as' => 'production.service.sorting', 'uses' => 'ServiceController@updateSorting']);
    $router->put((RE ? 'option/sorting' : '0001/0006'), ['as' => 'production.service.option.sorting', 'uses' => 'ServiceOptionController@updateSorting']);
    $router->put((RE ? 'option/configuration/resequence' : '0004/0006'), ['as' => 'production.service.option.configuration.resequence', 'uses' => 'ConfigurationController@updateSequence']);
    
    $router->post('/', ['as' => 'production.service.create', 'uses' => 'ServiceController@createService']);
    $router->put('/{id}', ['as' => 'production.service.update', 'uses' => 'ServiceController@updateService']);
    $router->delete('/{id}', ['as' => 'production.service.delete', 'uses' => 'ServiceController@deleteService']);

    $router->group(['prefix' => '/{id}/'.(RE ? 'option' : '0001')], function ($router) {
        $router->post('/', ['as' => 'production.service.option.create', 'uses' => 'ServiceOptionController@createOption']);
        $router->put('/{option_id}', ['as' => 'production.service.option.update', 'uses' => 'ServiceOptionController@updateOption']);
        $router->delete('/{option_id}', ['as' => 'production.service.option.delete', 'uses' => 'ServiceOptionController@deleteOption']);

        $router->group(['prefix' => '/{option_id}/'.(RE ? 'price' : '0003')], function ($router) {
            $router->post('/', ['as' => 'production.service.option.price.create', 'uses' => 'ServicePriceController@createPrice']);
            $router->put('/{price_id}', ['as' => 'production.service.option.price.update', 'uses' => 'ServicePriceController@updatePrice']);
            $router->delete('{price_id}', ['as' => 'production.service.option.price.delete', 'uses' => 'ServicePriceController@deletePrice']);
        });

        $router->group(['prefix' => '/{option_id}/'.(RE ? 'configuration' : '0004')], function ($router) {
            $router->post('/', ['as' => 'production.service.option.configuration.create', 'uses' => 'ConfigurationController@createConfiguration']);
            $router->put('/{configuration_id}', ['as' => 'production.service.option.configuration.update', 'uses' => 'ConfigurationController@updateConfiguration']);
            $router->delete('{configuration_id}', ['as' => 'production.service.option.configuration.delete', 'uses' => 'ConfigurationController@deleteConfiguration']);
        });
    });
});
