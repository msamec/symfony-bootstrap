<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Manager\UserManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class UserController
{
    /**
     * @var UserManager
     */
    private $manager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        UserManager $manager,
        SerializerInterface $serializer
    ) {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/users", methods={"GET"})
     * @SWG\Response(
     *     response="200",
     *     description="Retrieves the collection of User resources",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=User::class, groups={"user:index"}))
     *     )
     * )
     * @SWG\Tag(name="User")
     */
    public function index(): JsonResponse
    {
        $users = $this->manager->all();
        $json = $this->serializer->serialize($users, 'json', ['groups' => ['user:index']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/users/{id}", methods={"GET"})
     * @SWG\Response(
     *     response="200",
     *     description="Retrieves a User resource",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=User::class, groups={"user:get"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer"
     * )
     * @SWG\Tag(name="User")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function get(User $user): JsonResponse
    {
        $json = $this->serializer->serialize($user, 'json', ['groups' => ['user:get']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/users", methods={"POST"})
     * @SWG\Response(
     *     response="200",
     *     description="Creates a User resource",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=User::class, groups={"user:get"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=User::class, groups={"user:create"}))
     * )
     * @SWG\Tag(name="User")
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $user = $this->manager->create($request->request->all());
        $json = $this->serializer->serialize($user, 'json', ['groups' => ['user:get']]);

        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     * @SWG\Response(
     *     response="200",
     *     description="Replaces a User resource",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=User::class, groups={"user:get"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=User::class, groups={"user:update"}))
     * )
     * @SWG\Tag(name="User")
     *
     * @param Request $request
     * @param User $user
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $user = $this->manager->update($user, $request->request->all());
        $json = $this->serializer->serialize($user, 'json', ['groups' => ['user:get']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     * @SWG\Response(
     *     response="204",
     *     description="Removes a User resource",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     required=true
     * )
     * @SWG\Tag(name="User")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function delete(User $user): JsonResponse
    {
        $this->manager->delete($user);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}