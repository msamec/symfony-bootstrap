<?php

namespace App\Action\User;

use App\Entity\User;
use App\Factory\EntityFactory;
use App\Repository\UserRepository;

final class CreateUser
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

    public function execute(array $data): User
    {
        /** @var User $entity */
        $entity = $this->entityFactory->create(
            $data,
            User::class,
            ['groups' => ['user:create']]
        );

        $this->repository->save($entity);

        return $entity;
    }
}
