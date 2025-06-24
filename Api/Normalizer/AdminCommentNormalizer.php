<?php

namespace AdminComment\Api\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use AdminComment\Api\Resource\AdminComment as AdminCommentResource;
use AdminComment\Model\AdminComment;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

readonly class AdminCommentNormalizer implements NormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer
    ) {}

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof AdminCommentResource;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var AdminCommentResource $object */
        $data = $this->normalizer->normalize($object, $format, $context);

        /** @var AdminComment $model */
        $model = $object->getPropelModel();

        if ($model !== null && null !== $model->getAdmin()) {
            $admin = $model->getAdmin();
            $adminName = trim($admin->getFirstname() . ' ' . $admin->getLastname());
            $data['adminName'] = $adminName;
        }

        return $data;
    }
}
