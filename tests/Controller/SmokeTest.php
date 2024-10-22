<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    public function testApiDocUrlIsSuccessful(): void
    {
        $client = self::createClient();

        $client->request('POST', '/api/registration');

        $statusCode = $client->getResponse()->getStatusCode(); 
        dd($statusCode);
    }
}