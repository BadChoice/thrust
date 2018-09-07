<?php

/**
 * TODO:
 * [] Make visibleWhen (for checkboxes, or type of printers... etc)
 */

Route::group(['prefix' => 'thrust', 'namespace' => 'BadChoice\Thrust\Controllers', "middleware" => ['web' , 'auth']], function(){
    Route::get('{resourceName}/edit/{id}', 'ThrustController@edit')->name('thrust.edit');
    Route::put('{resourceName}/{id}', 'ThrustController@update')->name('thrust.update');
    Route::delete('{resourceName}/{id}', 'ThrustController@delete')->name('thrust.delete');
    Route::get('{resourceName}/search/{search}', 'ThrustController@search')->name('thrust.search');
});
