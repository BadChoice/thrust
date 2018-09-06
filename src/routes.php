<?php

Route::group(['namespace' => 'BadChoice\Thrust\Controllers', "middleware" => ['web' , 'auth']], function(){
    Route::get('thrust/{resource}/edit/{id}', 'ThrustController@edit')->name('thrust.edit');
    Route::put('thrust/{resource}/{id}', 'ThrustController@update')->name('thrust.update');
    Route::delete('thrust/{resource}/{id}', 'ThrustController@delete')->name('thrust.delete');
});
