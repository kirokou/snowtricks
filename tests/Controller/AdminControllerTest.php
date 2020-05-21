<?php

namespace App\Tests\Controller;

use App\Tests\NeedLogin;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    use NeedLogin;

    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
        parent::setUp();
    }

    public function testAdminIndexProtectedRouteAndRedirect()
    {
        $crawler = $this->client->request('GET', '/admin');

       $this->assertResponseRedirects('/login');
       $this->client->followRedirect();
    }

    public function testLetAdminAccessDashboard()
    {
        $this->logIn($this->client, $this->getUser('admin@gmail.com'));
        $this->client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testBlockAdminAccessDashboard()
    {
        $this->logIn($this->client, $this->getUser('user@gmail.com'));
        $this->client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }


    private function getUser (String $email = null)
    {
        $userRepository = static::$container->get(UserRepository::class);
        return $userRepository->findOneBy(['email' => $email]);
    }
}
