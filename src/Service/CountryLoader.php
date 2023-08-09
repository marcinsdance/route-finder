<?php

namespace App\Service;

use GuzzleHttp\ClientInterface;

class CountryLoader
{
    private const DATA_LINK = 'https://raw.githubusercontent.com/mledoze/countries/master/countries.json';

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function loadCountries(): array
    {
        $response = $this->client->request('GET', self::DATA_LINK);
        $jsonContent = (string) $response->getBody();

        return json_decode($jsonContent, true);
    }
}
