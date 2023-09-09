<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[Route('/api/user', name: 'api.user')]
class UserController extends BaseController
{
    private UserRepository $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('', name: '.create', methods: ['POST', 'GET'])]
    public function createUser(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTTokenManager
    ): JsonResponse {
        $data = $this->getData($request);

        if (
            !$data->has('username')
            || !$data->has('password')
        ) {
            return new JsonResponse([
                'message' => 'username and password are required',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (
            $this->repository->findBy([
                'username' => $data->get('username'),
            ])
        ) {
            return new JsonResponse([
                'message' => 'Username already taken',
            ], Response::HTTP_CONFLICT);
        }

        $entity = new User();
        $entity->setUsername($data->get('username'));

        $plainPassword = $data->get('password');
        $hashedPassword = $passwordHasher->hashPassword($entity, $plainPassword);
        $entity->setPassword($hashedPassword);

        $this->repository->save($entity, true);

        $result = $this->serializeEntity($entity, [
            'userIdentifier',
            'password',
            'roles',
        ]);
        $result['token'] = $JWTTokenManager->create($entity);

        return new JsonResponse($result);
    }

    #[Route('', name: '.delete', methods: ['DELETE'])]
    public function deleteUser(): JsonResponse
    {
        /* @noinspection PhpParamsInspection */
        $this->repository->remove($this->getUser(), true);

        return new JsonResponse(['message' => 'User deleted'], Response::HTTP_OK);
    }

    #[Route('/{entity}', name: '.delete_by_id', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUserById(
        ?User $entity
    ): JsonResponse {
        if (!$entity) {
            return new JsonResponse(['message' => 'User does not exist', Response::HTTP_NOT_FOUND]);
        }

        $this->repository->remove($entity, true);

        return new JsonResponse(['message' => 'User deleted'], Response::HTTP_OK);
    }
}
