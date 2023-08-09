<?php

namespace App\Tests\Service;

use App\Service\CountryLoader;
use App\Service\RouteService;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class RouteServiceTest extends TestCase
{
    private RouteService $routeService;

    protected function setUp(): void
    {
        $countryLoader = $this->createMock(CountryLoader::class);
        $countryLoader->method('loadCountries')->willReturn([
            [
                'cca3' => 'CZE',
                'borders' => ['AUT'],
            ],
            [
                'cca3' => 'AUT',
                'borders' => ['CZE', 'ITA'],
            ],
            [
                'cca3' => 'ITA',
                'borders' => ['AUT'],
            ],
        ]);

        $this->routeService = new RouteService($countryLoader);
    }

    public function testCalculateRouteWithValidPath(): void
    {
        $route = $this->routeService->calculateRoute('CZE', 'ITA');
        $this->assertEquals(['CZE', 'AUT', 'ITA'], $route);
    }

    public function testCalculateRouteWithNoPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Origin or destination not found.');

        $this->routeService->calculateRoute('CZE', 'ISL');
    }

    public function testCalculateRouteWithInvalidOrigin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Origin or destination not found.');

        $routeService = new RouteService($this->createMock(CountryLoader::class));
        $routeService->calculateRoute('INVALID_ORIGIN', 'VALID_DESTINATION');
    }

    public function testCalculateRouteWithInvalidDestination(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Origin or destination not found.');

        $this->routeService->calculateRoute('CZE', 'INVALID');
    }
}
