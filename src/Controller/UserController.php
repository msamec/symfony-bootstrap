<?php

namespace App\Controller;

use App\Action\User\CreateUser;
use App\Action\User\DeleteUser;
use App\Action\User\FetchUser;
use App\Action\User\UpdateUser;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class UserController
{
    private $serializer;

    public function __construct(
        SerializerInterface $serializer
    ) {
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
     *
     * @param FetchUser $fetchUser
     *
     * @return JsonResponse
     */
    public function index(FetchUser $fetchUser): JsonResponse
    {
        $users = $fetchUser->all();
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
     * @param Request    $request
     * @param CreateUser $createUser
     *
     * @return JsonResponse
     */
    public function create(Request $request, CreateUser $createUser): JsonResponse
    {
        $user = $createUser->execute($request->request->all());
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
     * @param Request    $request
     * @param User       $user
     * @param UpdateUser $updateUser
     *
     * @return JsonResponse
     */
    public function update(Request $request, User $user, UpdateUser $updateUser): JsonResponse
    {
        $user = $updateUser->execute($user, $request->request->all());
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
     * @param User       $user
     * @param DeleteUser $deleteUser
     *
     * @return JsonResponse
     */
    public function delete(User $user, DeleteUser $deleteUser): JsonResponse
    {
        $deleteUser->execute($user);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
