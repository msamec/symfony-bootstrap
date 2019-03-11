<?php

namespace App\Action\User;

use App\Entity\User;
use App\Repository\UserRepository;

final class DeleteUser
{
    private $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute(User $user): void
    {
        $this->repository->remove($user);
    }
}
