<?php

namespace App\Action\User;

use App\Entity\User;
use App\Repository\UserRepository;

final class FetchUser
{
    private $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function all(): array
    {
        return $this->repository->findAll();
    }

    public function byEmail(string $email): User
    {
        /** @var User $user */
        $user = $this->repository->findOneBy(['email' => $email]);

        return $user;
    }
}
