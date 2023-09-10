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
    public function getPrecipitation(
        float $latitude,
        float $longitude,
    ): ArrayCollection {
        $url = sprintf(
            'https://gpsgadget.buienradar.nl/data/raintext?lat=%s&lon=%s',
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

    // @TODO move to RainData.php, add to serializer
    public function getPrecipitationInMillimetres(int $precipitation): string
    {
        if (0 === $precipitation) {
            return '0 mm/uur';
        }

        return 10 * (($precipitation - 109) / 32).' mm/uur';
    }
}
