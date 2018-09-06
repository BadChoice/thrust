<?php

Route::group(['namespace' => 'BadChoice\Thrust\Controllers', "middleware" => ['web' , 'auth']], function(){
    Route::get('thrust/{resourceName}/edit/{id}', 'ThrustController@edit')->name('thrust.edit');
    Route::put('thrust/{resourceName}/{id}', 'ThrustController@update')->name('thrust.update');
    Route::delete('thrust/{resourceName}/{id}', 'ThrustController@delete')->name('thrust.delete');
    Route::get('thrust/{resourceName}/search/{search}', 'ThrustController@search')->name('thrust.search');
});
