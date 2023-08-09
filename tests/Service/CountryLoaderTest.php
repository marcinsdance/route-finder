<?php

namespace App\Tests\Service;

use App\Service\CountryLoader;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CountryLoaderTest extends TestCase
{
    public function testLoadCountries()
    {
        $json = '[{"cca3": "CZE", "borders": ["AUT", "ITA"]}]';
        $response = new Response(200, [], $json);

        $client = $this->createMock(ClientInterface::class);
        $client->method('request')->willReturn($response);

        $countryLoader = new CountryLoader($client);
        $countries = $countryLoader->loadCountries();

        $this->assertIsArray($countries);
        $this->assertCount(1, $countries);
        $this->assertEquals('CZE', $countries[0]['cca3']);
        $this->assertEquals(['AUT', 'ITA'], $countries[0]['borders']);
    }
}
