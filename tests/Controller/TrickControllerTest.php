<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickControllerTest extends WebTestCase
{
    public function testTrickControllerIndexMethodeRoute()
    {
        $client = static::createClient();
         $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
