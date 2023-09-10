<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\BuienradarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RainDataController extends AbstractController
{
    private BuienradarService $buienradarService;

    public function __construct(
        BuienradarService $buienradarService
    ) {
        $this->buienradarService = $buienradarService;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getForLocation(
        Location $location,
    ): JsonResponse {
        $latitude = $location->getLatitude();
        $longitude = $location->getLongitude();

        $result = $this->buienradarService->getPrecipitation($latitude, $longitude);

        // @TODO return a proper response - hydra:Error Unable to generate an IRI for the item of type \"App\\Entity\\RainData\"
        return new JsonResponse([
            'message' => sprintf('%d records written', $result->count()),
        ]);
    }
}
