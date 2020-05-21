<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistrationRouteSuccessfullGET()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sign-up');

        $this->assertResponseIsSuccessful();
    }

    public function testRegistrationRouteSuccessfullPOST()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/sign-up');

        $this->assertResponseIsSuccessful();
    }

    //Tester les 2 return de la fonction 
    public function testRegistrationRouteGETRenderSame()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sign-up');
        
    }
}
