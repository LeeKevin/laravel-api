<?php

class UsersTest extends TestCase
{

    protected $access_token;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');
        $this->access_token = $this->getToken($this->userEmail, $this->userPassword);
    }

    public function testCreatesUser()
    {
        $this
            ->post('/api/v1/users', [
                'firstname' => 'testuser',
                'lastname'  => 'testuser',
                'email'     => 'users@test.com',
                'password'  => 'test'
            ], [
                'Authorization' => 'Bearer ' . $this->access_token
            ]);

        $JSON = $this->response->getContent();
        $this->assertJson($JSON);
        $response = json_decode($JSON, true);
        $this->assertArrayHasKey('user_id', $response);
    }

    public function testFailsToCreateUser()
    {
        $this
            ->post('/api/v1/users', [], [
                'Authorization' => 'Bearer ' . $this->access_token
            ]);

        $JSON = $this->response->getContent();
        $this->assertJson($JSON);
        $response = json_decode($JSON, true);
        $this->assertArrayHasKey('errors', $response);
    }

    public function testViewUser()
    {
        $user = $this->findUser($this->userEmail);
        $this
            ->get('/api/v1/users/' . $user->id, [
                'Authorization' => 'Bearer ' . $this->access_token
            ])->seeJson(['id' => $user->id, 'email' => $this->userEmail]);
    }
}