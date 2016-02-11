<?php

Route::group(['prefix' => 'api/v1'], function () {
    //Retrieve token
    Route::post('token', ['uses' => 'UsersController@token', 'middleware' => 'auth']);

    //Apply VerifyToken middleware to api
    Route::group(['middleware' => 'token'], function () {
        Route::resource('users', 'UsersController', ['except' => ['create', 'edit']]);
    });
});

Route::get('/', function (\App\Domain\Repositories\UserRepository $ur, \Doctrine\ORM\EntityManagerInterface $em) {
    $user = \App\Domain\Entities\User::create([
        'name' => \App\Domain\ValueObjects\Name::create('test', 'name'),
        'email' => 'email3',
        'password' => 'password'
    ])->save();

    $results = $ur->findBy(['name.firstname' => 'first']);
    return 'test';
});