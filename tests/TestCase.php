<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
    protected $userEmail = 'test@test.com';
    protected $userPassword = 'testPassword';

    /**
     * @var \EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function getToken($email, $password)
    {
        $this
            ->post('/api/v1/token', [], [
                'Authorization' => 'Basic ' . base64_encode($email . ':' . $password)
            ])
            ->seeStatusCode(200);

        $tokenJSON = $this->response->getContent();
        $this->assertJson($tokenJSON);
        $token = json_decode($tokenJSON, true);
        $this->assertArrayHasKey('access_token', $token);

        return $token['access_token'];
    }

    /**
     * @param $email
     * @return \App\Domain\Entities\User|null
     */
    protected function forceLogin($email)
    {
        $user = $this->findUser($email);
        $this->assertNotNull($user, 'Could not find user.');
        \Auth::login($user);

        return $user;
    }

    /**
     * @param $email
     * @return null|App\Domain\Entities\User
     */
    protected function findUser($email) {
        $this->loadEntityManager();

        return $this->entityManager->getRepository(App\Domain\Entities\User::class)->findOneBy(['email' => $email]);
    }

    /**
     * @return EntityManager
     */
    protected function loadEntityManager() {
        if (!$this->entityManager instanceof EntityManager) $this->entityManager = app()['em'];

        return $this->entityManager;
    }
}
