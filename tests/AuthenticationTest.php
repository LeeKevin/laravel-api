<?php

class AuthenticationTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    public function testRetrievesToken()
    {
        $this->getToken($this->userEmail, $this->userPassword);
    }

    public function testFailsToRetrieveToken()
    {
        $this
            ->post('/api/v1/token', [], [
                'Authorization' => 'Basic ' . base64_encode('fail:test')
            ])
            ->seeStatusCode(401)
            ->seeJson(["error_description" => "The supplied credentials are invalid."]);

        $this
            ->post('/api/v1/token')
            ->seeStatusCode(401)
            ->seeJson(["error_description" => "You must supply valid credentials."]);
    }


    public function testRefreshesToken()
    {
        $this
            ->post('/api/v1/token', [], [
                'Authorization' => 'Bearer ' . $this->getToken($this->userEmail, $this->userPassword)
            ])
            ->seeStatusCode(200);

        $tokenJSON = $this->response->getContent();
        $this->assertJson($tokenJSON);
        $token = json_decode($tokenJSON, true);
        $this->assertArrayHasKey('access_token', $token);

        return $token['access_token'];
    }
}
