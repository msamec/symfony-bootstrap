<?php

namespace App\Manager;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Factory\EntityFactory;
use App\Repository\UserRepository;

/**
 * Class UserManager.
 */
final class UserManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityFactory
     */
    private $entityFactory;

    public function __construct(
        UserRepository $userRepository,
        EntityFactory $entityFactory
    ) {
        $this->userRepository = $userRepository;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param array $data
     *
     * @return User
     *
     * @throws ValidationException
     */
    public function create(array $data): User
    {
        /** @var User $entity */
        $entity = $this->entityFactory->create(
            $data,
            User::class,
            ['groups' => ['user:create']]
        );

        $this->userRepository->save($entity);

        return $entity;
    }

    /**
     * @param User  $user
     * @param array $data
     *
     * @return User
     *
     * @throws ValidationException
     */
    public function update(User $user, array $data): User
    {
        /** @var User $entity */
        $entity = $this->entityFactory->create(
            $data,
            User::class,
            ['object_to_populate' => $user, 'groups' => ['user:update']]
        );

        $this->userRepository->save($entity);

        return $entity;
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        $this->userRepository->remove($user);
    }
}
