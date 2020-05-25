<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistrationRouteSuccessfullGET()
    {
        $client = static::createClient();
        $client->request('GET', '/sign-up');

        $this->assertResponseIsSuccessful();
    }

    public function testRegistrationRouteSuccessfullPOST()
    {
        $client = static::createClient();
        $client->request('POST', '/sign-up');

        $this->assertResponseIsSuccessful();
    }
}
