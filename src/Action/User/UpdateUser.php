<?php

namespace App\Action\User;

use App\Entity\User;
use App\Factory\EntityFactory;
use App\Repository\UserRepository;

final class UpdateUser
{
    private $entityFactory;
    private $repository;

    public function __construct(
        EntityFactory $entityFactory,
        UserRepository $repository
    ) {
        $this->entityFactory = $entityFactory;
        $this->repository = $repository;
    }

    public function execute(User $user, array $data): User
    {
        /** @var User $entity */
        $entity = $this->entityFactory->create(
            $data,
            User::class,
            ['object_to_populate' => $user, 'groups' => ['user:update']]
        );

        $this->repository->save($entity);

        return $entity;
    }
}
