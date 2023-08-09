<?php

namespace App\Service;

use InvalidArgumentException;
use SplQueue;

class RouteService
{
    private array $graph = [];

    public function __construct(CountryLoader $countryLoader)
    {
        $this->buildGraph($countryLoader->loadCountries());
    }

    private function buildGraph(array $countries): void
    {
        foreach ($countries as $country) {
            if (!isset($country['cca3'], $country['borders'])) {
                throw new InvalidArgumentException("Invalid country data provided.");
            }

            $this->graph[$country['cca3']] = $country['borders'];
        }
    }

    /**
     * Calculate the route between two countries.
     *
     * @param string $origin The origin country code.
     * @param string $destination The destination country code.
     * @return array|null The route as an array of country codes, or null if no route is found.
     * @throws InvalidArgumentException If origin or destination is not found.
     */
    public function calculateRoute(string $origin, string $destination): ?array
    {
        $this->validateCountries($origin, $destination);

        $visited = [];
        $queue = new SplQueue();
        $queue->enqueue([$origin]);

        while (!$queue->isEmpty()) {
            $path = $queue->dequeue();
            $country = end($path);

            if ($country === $destination) {
                return $path;
            }

            $this->processNeighbors($country, $visited, $queue, $path);
        }

        return null;
    }

    private function validateCountries(string $origin, string $destination): void
    {
        if (!isset($this->graph[$origin]) || !isset($this->graph[$destination])) {
            throw new InvalidArgumentException("Origin or destination not found.");
        }
    }

    private function processNeighbors(string $country, array &$visited, SplQueue $queue, array $path): void
    {
        if (!isset($visited[$country])) {
            $neighbors = $this->graph[$country];
            foreach ($neighbors as $neighbor) {
                $newPath = $path;
                $newPath[] = $neighbor;
                $queue->enqueue($newPath);
            }

            $visited[$country] = true;
        }
    }
}
