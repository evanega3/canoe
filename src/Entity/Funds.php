<?php

declare(strict_types=1);

namespace App\Entity;

use App\ApiResource\DTO\Fund\RequestUpdateFundData;
use App\Repository\Funds\FundsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundsRepository::class)]
class Funds
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_year = null;

    #[ORM\ManyToOne(inversedBy: 'funds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Companies $manager = null;

    #[ORM\Column(nullable: true)]
    private ?array $aliases = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    public function __construct(
        string $name,
        Companies $manager,
        array $aliases
    ) {
        $this->name = $name;
        $this->manager = $manager;
        $this->aliases = $aliases;
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartYear(): ?\DateTimeInterface
    {
        return $this->start_year;
    }

    public function setStartYear(?\DateTimeInterface $start_year): static
    {
        $this->start_year = $start_year;

        return $this;
    }

    public function getAliases(): ?array
    {
        return $this->aliases;
    }

    public function setAliases(?array $aliases): static
    {
        $this->aliases = $aliases;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function getManager(): ?Companies
    {
        return $this->manager;
    }

    public function setManager(?Companies $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    public function toArrayResponse(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'start_year' => $this->getStartYear(),
            'manager' => [
                'id' => $this->getManager()->getId(),
                'name' => $this->getManager()->getName(),
                'created_at' => $this->getManager()->getCreatedAt(),
            ],
            'aliases' => $this->getAliases(),
            'created_at' => $this->getCreatedAt(),
        ];
    }

    /**
     * @throws \Exception
     */
    public function updateFromRequestFundData(RequestUpdateFundData $requestData): void
    {
        if (null !== $requestData->getName()) {
            $this->setName($requestData->getName());
        }

        if (null !== $requestData->getStartYear()) {
            $this->setStartYear(new \DateTime($requestData->getStartYear()));
        }

        if (null !== $requestData->getManager()) {
            $this->setManager($requestData->getManager());
        }

        if (null !== $requestData->getAliases()) {
            $mergedAliases = array_unique(array_merge($this->getAliases(), $requestData->getAliases()));
            $this->setAliases($mergedAliases);
        }
    }
}
