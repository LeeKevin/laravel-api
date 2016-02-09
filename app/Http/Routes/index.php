<?php

Route::group(['prefix' => 'api/v1'], function () {
    Route::post('token', ['uses' => 'UsersController@token', 'middleware' => 'basicauth']);

    Route::resource('users', 'UsersController');
});
