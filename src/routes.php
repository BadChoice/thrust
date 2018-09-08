<?php

/**
 * TODO:
 * [] Make visibleWhen (for checkboxes, or type of printers... etc)
 * [] Use the search route into searcher, and pass the search parameter to query instead of a new url path parameter
 * [] Make check fields to be toggable from the index
 * [] Pin validation not working (digits 4)
 * [] Update / store validation
 * [] Delete validation
 * [] Configurable route prefix
 * [] Employee, photo upload...
 */

Route::group(['prefix' => 'thrust', 'namespace' => 'BadChoice\Thrust\Controllers', "middleware" => ['web' , 'auth']], function(){
    Route::get('{resourceName}/edit/{id}', 'ThrustController@edit')->name('thrust.edit');
    Route::put('{resourceName}/{id}', 'ThrustController@update')->name('thrust.update');
    Route::delete('{resourceName}/{id}', 'ThrustController@delete')->name('thrust.delete');
    Route::get('{resourceName}/search/{search}', 'ThrustController@search')->name('thrust.search');

    Route::get('{resourceName}/{id}/related/{relationship}', 'ThrustRelationshipController@search')->name('thrust.relationship.search');
});
