<?php

namespace App\Factory;

use App\Entity\EntityInterface;
use App\Exception\ValidationException;
use App\Service\ValidatorService;
use JsonSerializable;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class EntityFactory.
 */
final class EntityFactory
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ValidatorService
     */
    private $validator;

    /**
     * EntityFactory constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorService    $validator
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorService $validator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param mixed  $data
     * @param string $class
     * @param array  $context
     *
     * @return EntityInterface
     *
     * @throws ValidationException
     */
    public function create($data, string $class, array $context = []): EntityInterface
    {
        if ($data instanceof JsonSerializable || \is_array($data)) {
            $data = json_encode($data);
        }

        /** @var EntityInterface $entity */
        $entity = $this->serializer->deserialize($data, $class, 'json', $context);

        $groups = $context['groups'] ?? [];
        if (!\is_array($groups)) {
            $groups = [$groups];
        }
        $groups[] = 'Default';

        $this->validator->validate($entity, $groups);

        return $entity;
    }
}
