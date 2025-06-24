<?php

namespace AdminComment\Api\Resource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use AdminComment\Model\Map\AdminCommentTableMap;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Thelia\Api\Bridge\Propel\Filter\OrderFilter;
use Thelia\Api\Bridge\Propel\Filter\SearchFilter;
use Thelia\Api\Bridge\Propel\State\PropelCollectionProvider;
use Thelia\Api\Resource\PropelResourceInterface;
use Thelia\Api\Resource\PropelResourceTrait;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: 'admin/admin-comments',
            paginationEnabled: true,
            provider: PropelCollectionProvider::class
        ),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ_ADMIN]]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: 'front/admin-comments',
            paginationEnabled: true,
            provider: PropelCollectionProvider::class
        ),
    ],
    normalizationContext: ['groups' => [self::GROUP_READ_FRONT]]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'id' => 'exact',
    'elementKey' => 'exact',
    'elementId' => 'exact',
])]
#[ApiFilter(OrderFilter::class, properties: [
    'id',
    'createdAt',
    'updatedAt',
])]
class AdminComment implements PropelResourceInterface
{
    use PropelResourceTrait;

    public const GROUP_READ_ADMIN = 'admin:admin_comment:read';
    public const GROUP_READ_FRONT = 'front:admin_comment:read';

    #[Groups([self::GROUP_READ_ADMIN, self::GROUP_READ_FRONT])]
    private ?int $id = null;

    #[Groups([self::GROUP_READ_ADMIN, self::GROUP_READ_FRONT])]
    private ?int $adminId = null;

    #[Groups([self::GROUP_READ_ADMIN, self::GROUP_READ_FRONT])]
    private ?string $comment = null;

    #[Groups([self::GROUP_READ_ADMIN, self::GROUP_READ_FRONT])]
    private ?string $elementKey = null;

    #[Groups([self::GROUP_READ_ADMIN, self::GROUP_READ_FRONT])]
    private ?int $elementId = null;

    #[Groups([self::GROUP_READ_ADMIN, self::GROUP_READ_FRONT])]
    private ?\DateTimeInterface $createdAt = null;

    #[Groups([self::GROUP_READ_ADMIN, self::GROUP_READ_FRONT])]
    private ?\DateTimeInterface $updatedAt = null;

    #[Groups([self::GROUP_READ_ADMIN, self::GROUP_READ_FRONT])]
    private ?string $adminName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getAdminId(): ?int
    {
        return $this->adminId;
    }

    public function setAdminId(?int $adminId): self
    {
        $this->adminId = $adminId;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getElementKey(): ?string
    {
        return $this->elementKey;
    }

    public function setElementKey(?string $elementKey): self
    {
        $this->elementKey = $elementKey;
        return $this;
    }

    public function getElementId(): ?int
    {
        return $this->elementId;
    }

    public function setElementId(?int $elementId): self
    {
        $this->elementId = $elementId;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getAdminName(): ?string
    {
        return $this->adminName;
    }

    public function setAdminName(?string $adminName): self
    {
        $this->adminName = $adminName;
        return $this;
    }

    /**
     * @throws PropelException
     */
    #[Ignore]
    public static function getPropelRelatedTableMap(): ?TableMap
    {
        return AdminCommentTableMap::getTableMap();
    }
}
