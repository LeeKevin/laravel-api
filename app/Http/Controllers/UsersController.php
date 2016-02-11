<?php

namespace App\Http\Controllers;

use App\Domain\Entities\User;
use App\Domain\Repositories;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param Repositories\UserRepository $userRepository
     */
    public function __construct(Repositories\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->all();
        $usersObject = [];

        if (is_array($users)) {
            /**
             * @var User $user
             */
            foreach ($users as $user) {
                $usersObject[] = [
                    'id'        => $user->id,
                    'email'     => $user->email,
                    'firstname' => $user->name->getFirstname(),
                    'lastname'  => $user->name->getLastname(),
                ];
            }
        }

        return $this->toJSONResponse(['users' => $usersObject]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $input = \Input::only('firstname', 'lastname', 'email', 'password');

        $user = User::create($input);

        // attempt validation
        if ($user->valid()) {
            $user->save();
        } else {
            // failure, get errors
            $errors = $user->errors();

            return $this->toJSONResponse([
                'errors' => $errors->getMessages(),
                'status' => 400
            ], 400);
        }

        return $this->toJSONResponse([
            'user_id' => $user->id
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if ($user) {
            return $this->toJSONResponse([
                'id'        => $user->id,
                'firstname' => $user->name->firstname,
                'lastname'  => $user->name->lastname,
                'email'     => $user->email,
            ], 200);
        }

        return $this->toJSONResponse([
            'error' => [
                'status'  => 400,
                'message' => 'Invalid user id: ' . $id
            ]
        ], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = \Input::only('firstname', 'lastname', 'email', 'password');
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->toJSONResponse([
                'error' => [
                    'status'  => 400,
                    'message' => 'Invalid user id: ' . $id
                ]
            ], 400);
        }

        // attempt validation
        if ($user->fill($input)->valid()) {
            $user->save();
        } else {
            // failure, get errors
            $errors = $user->errors();

            return $this->toJSONResponse([
                'errors' => $errors->getMessages(),
                'status' => 400
            ], 400);
        }

        return $this->toJSONResponse([
            'user_id' => $user->id
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->toJSONResponse([
                'error' => [
                    'status'  => 400,
                    'message' => 'Invalid user id: ' . $id
                ]
            ], 400);
        }

        $user->remove();

        return $this->toJSONResponse([]);
    }

    /**
     * Generate a new token for an authenticated user
     *
     * @return \Illuminate\Http\Response
     * @throws InvalidCredentialsException
     */
    public function token()
    {
        if (!$token = \JWTAuth::fromUser(\Auth::user())) throw new \App\Exceptions\InvalidCredentialsException;

        return $this->toJSONResponse([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => \Config::get('jwt.ttl', '60') * 60
        ]);
    }
}
