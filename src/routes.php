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
/**
 * TODO:
 * [x] Migrate to select2 4.0
 * [x] Make check fields to be toggable from the index
 * [x] Pin validation not working (digits 4)
 * [x] Update validation
 * [x] Configurable route prefix
 * [x] Make the service provider deffered as it doesn't need to be called in the API
 * [x] Panel visibility by check and select
 * [x] Make visibleWhen (for checkboxes, or type of printers... etc)
 * [x] BelongsTo many ajax searchable
 * [x] Prunable files, should be deleted when deleting resource
 * [x] Update saveOrder function to use a thrust one instead of the retail/xef yet
 * [x] Update saveOrder function to use the plural version of the resource name (the one we use on whole thrust) instead of the singular one
 * [x] ThrustRelationshipController to use the $relationDisplayName instead of `name`
 * [x] Employee, photo upload...
 * [x] Add save and continue editing functionality
 * [x] Inline editing!
 * [x] Table density
 * [] Quan sortable, afegir x defecte la main acction save order
 * [] Use the search route into searcher, and pass the search parameter to query instead of a new url path parameter
 * [] Delete validation
 * [] Make the resource found in app service provider recursive into thrust directory
 * [] Make sortable relationships (right now it uses the relationship name instead of the underling field)
 * [] Add latlang to algolia places search?
 * [] Improved ResourceFilters\Search by words, fer-ho per el primer fields nomes?
 * [] Search limitar-lo a 100 (configurable) sense pagination
 */

Route::group(['prefix' => config('thrust.routePrefix','thrust'), 'namespace' => 'BadChoice\Thrust\Controllers', "middleware" => ['web' , 'auth']], function(){
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

    Route::get('{resourceName}/{id}/image/{field}', 'ThrustImageController@edit')->name('thrust.image.edit');
    Route::post('{resourceName}/{id}/image/{field}', 'ThrustImageController@store')->name('thrust.image.store');
    Route::delete('{resourceName}/{id}/image/{field}', 'ThrustImageController@delete')->name('thrust.image.delete');

    Route::get('{resourceName}/{id}/belongsToMany/{field}', 'ThrustBelongsToManyController@index')->name('thrust.belongsToMany');
    Route::post('{resourceName}/{id}/belongsToMany/{field}', 'ThrustBelongsToManyController@store')->name('thrust.belongsToMany.store');
    Route::delete('{resourceName}/{id}/belongsToMany/{field}/{detachId}', 'ThrustBelongsToManyController@delete')->name('thrust.belongsToMany.delete');

    Route::get('{resourceName}/{id}/hasMany/{field}', 'ThrustHasManyController@index')->name('thrust.hasMany');

    Route::get('{resourceName}/{id}/related/{relationship}', 'ThrustRelationshipController@search')->name('thrust.relationship.search');
});
