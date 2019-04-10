<?php

/**
 * As local repository
 *
 *
    "repositories": [
        {
            "type": "path",
            "url": "../packages/thrust",
            "options" : {
            "symlink" : true
        }
    }
],
 */

Route::group(['prefix' => config('thrust.routePrefix', 'thrust'), 'namespace' => 'BadChoice\Thrust\Controllers', 'middleware' => config('thrust.routeMiddleware', ['web','auth'])], function () {
    Route::post('{resourceName}/actions', 'ThrustActionsController@perform')->name('thrust.actions.perform');
    Route::get('{resourceName}/actions', 'ThrustActionsController@create')->name('thrust.actions.create');

    Route::post('{resourceName}/updateOrder', 'ThrustSortController@updateOrder')->name('thrust.updateOrder');
    Route::get('{resourceName}/{id}/toggle/{field}', 'ThrustActionsController@toggle')->name('thrust.toggle');

    Route::get('{resourceName}', 'ThrustController@index')->name('thrust.index');
    Route::post('{resourceName}', 'ThrustController@store')->name('thrust.store');
    Route::get('{resourceName}/create', 'ThrustController@create')->name('thrust.create');
    Route::get('{resourceName}/{id}/edit', 'ThrustController@edit')->name('thrust.edit');
    Route::get('{resourceName}/{id}/editInline', 'ThrustController@editInline')->name('thrust.editInline');
    Route::put('{resourceName}/{id}', 'ThrustController@update')->name('thrust.update');
    Route::delete('{resourceName}/{id}', 'ThrustController@delete')->name('thrust.delete');
    Route::get('{resourceName}/search/{search}', 'ThrustSearchController@index')->name('thrust.search');
    Route::get('{resourceName}/export', 'ThrustExportController@index')->name('thrust.export');

    Route::get('{resourceName}/{id}/image/{field}', 'ThrustImageController@edit')->name('thrust.image.edit');
    Route::post('{resourceName}/{id}/image/{field}', 'ThrustImageController@store')->name('thrust.image.store');
    Route::delete('{resourceName}/{id}/image/{field}', 'ThrustImageController@delete')->name('thrust.image.delete');

    Route::get('{resourceName}/{id}/file/{field}', 'ThrustFileController@edit')->name('thrust.file.edit');
    Route::post('{resourceName}/{id}/file/{field}', 'ThrustFileController@store')->name('thrust.file.store');
    Route::delete('{resourceName}/{id}/file/{field}', 'ThrustFileController@delete')->name('thrust.file.delete');

    Route::get('{resourceName}/{id}/belongsToMany/{field}', 'ThrustBelongsToManyController@index')->name('thrust.belongsToMany');
    Route::post('{resourceName}/{id}/belongsToMany/{field}', 'ThrustBelongsToManyController@store')->name('thrust.belongsToMany.store');
    Route::get('{resourceName}/{id}/belongsToMany/{field}/search/{search}', 'ThrustBelongsToManyController@search')->name('thrust.belongsToMany.search');
    Route::post('{resourceName}/{id}/belongsToMany/{field}/updateOrder', 'ThrustBelongsToManyController@updateOrder')->name('thrust.belongsToMany.updateOrder');
    Route::delete('{resourceName}/{id}/belongsToMany/{field}/{detachId}', 'ThrustBelongsToManyController@delete')->name('thrust.belongsToMany.delete');

    Route::get('{resourceName}/{id}/hasMany/{field}', 'ThrustHasManyController@index')->name('thrust.hasMany');

    Route::get('{resourceName}/{id}/related/{relationship}', 'ThrustRelationshipController@search')->name('thrust.relationship.search');

    Route::get('thrust/metric/{metric}', 'ThrustMetricsController@show')->name('thrust.metric');
});
