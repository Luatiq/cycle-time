<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/locations', name: 'api.locations')]
class LocationController extends BaseController
{
    private LocationRepository $repository;

    public function __construct(
        LocationRepository $repository
    ) {
        $this->repository = $repository;
    }

    #[Route('', name: '.get_all', methods: ['GET'])]
    public function getLocations(): JsonResponse
    {
        return new JsonResponse(
            $this->serializeEntity(
                $this->getUser()->getLocations(),
                //                ['user'],
            )
        );
    }

    #[Route('', name: '.add', methods: ['POST'])]
    public function addLocation(Request $request): JsonResponse
    {
        $data = $this->getData($request);

        $entity = new Location();
        $entity->setLatitude($data->get('latitude'));
        $entity->setLongitude($data->get('longitude'));
        $entity->setDisplay($data->get('display'));
        // @TODO use prepersist listener (/blameable?)
        $entity->setUser($this->getUser());

        $this->repository->save($entity, true);

        return new JsonResponse(
            $this->serializeEntity(
                $this->getUser()->getLocations(),
                [
                    'locations',
                ],
            )
        );
    }
}
