<?php

Route::group(['prefix' => 'api/v1'], function () {
    //Retrieve or refresh token
    Route::post('token', ['uses' => 'UsersController@token', 'middleware' => 'auth']);

    //Apply VerifyToken middleware to api
    Route::group(['middleware' => 'token'], function () {
        Route::resource('users', 'UsersController', ['except' => ['create', 'edit']]);
    });
});