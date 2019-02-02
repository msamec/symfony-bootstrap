<?php

namespace App\Controller;

use App\Manager\UserManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class TokenController
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var JWTEncoderInterface
     */
    private $encoder;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        UserManager $userManager,
        JWTEncoderInterface $encoder,
        SerializerInterface $serializer
    ) {
        $this->userManager = $userManager;
        $this->encoder = $encoder;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/tokens", methods={"POST"})
     * @SWG\Response(
     *     response="200",
     *     description="Retrieves the token",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *             property="email",
     *             type="string"
     *         )
     *     )
     * )
     * @SWG\Tag(name="Authentication")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function create(Request $request): JsonResponse
    {
        $user = $this->userManager->findByEmail($request->request->get('email'));
        if (!$user) {
            throw new NotFoundHttpException('User not found!');
        }

        $token = $this->encoder
            ->encode([
                'username' => $user->getUsername(),
            ]);

        $json = $this->serializer->serialize(['token' => $token], 'json');

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
