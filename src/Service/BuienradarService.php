<?php

namespace App\Service;

use App\Entity\RainData;
use App\Repository\RainDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BuienradarService
{
    public const BASE_URL = 'https://gpsgadget.buienradar.nl';

    private RainDataRepository $rainDataRepository;
    private HttpClientInterface $httpClient;

    public function __construct(
        RainDataRepository $rainDataRepository,
        HttpClientInterface $httpClient
    ) {
        $this->rainDataRepository = $rainDataRepository;
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function updatePrecipitation(
        float $latitude,
        float $longitude,
    ): ArrayCollection {
        $url = sprintf(
            self::BASE_URL.'/data/raintext?lat=%s&lon=%s',
            $latitude,
            $longitude,
        );

        $response = $this->httpClient->request('GET', $url)->getContent();

        $result = new ArrayCollection();
        foreach (explode("\n", $response) as $line) {
            if (!str_contains($line, '|')) {
                continue;
            }

            $lineValues = explode('|', $line);

            $entity = new RainData();
            $entity->setLatitude($latitude);
            $entity->setLongitude($longitude);
            $entity->setPrecipitationIntensity((int) $lineValues[0]);
            $entity->setTime(new \DateTimeImmutable($lineValues[1]));

            $this->rainDataRepository->save($entity);
            $result->add($entity);
        }

        $this->rainDataRepository->flush();

        return $result;
    }
}
