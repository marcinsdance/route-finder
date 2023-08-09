<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutingControllerTest extends WebTestCase
{
    public function testRouting(): void
    {
        $client = static::createClient();
        $client->request('POST', '/routing/CZE/ITA');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseContent['route']);
        $this->assertEquals(['CZE', 'AUT', 'ITA'], $responseContent['route']);
    }

    public function testRoutingWithInvalidOrigin(): void
    {
        $client = static::createClient();
        $client->request('POST', '/routing/invalid/destination');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testRoutingWithNoPath(): void
    {
        $client = static::createClient();
        $client->request('POST', '/routing/originCountryCode/isolatedCountryCode');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}
