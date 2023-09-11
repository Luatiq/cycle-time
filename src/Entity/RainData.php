<?php

namespace App\Entity;

use App\Repository\RainDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: RainDataRepository::class)]
class RainData
{
    // @TODO remove somehow, this is a hack to get RainData by unrelated location ID
    public string $location = '';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[Timestampable(on: 'create')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $time = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $precipitationIntensity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTime(): ?\DateTimeImmutable
    {
        return $this->time;
    }

    public function setTime(\DateTimeImmutable $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getPrecipitationIntensity(): ?int
    {
        return $this->precipitationIntensity;
    }

    public function setPrecipitationIntensity(int $precipitationIntensity): static
    {
        $this->precipitationIntensity = $precipitationIntensity;

        return $this;
    }

    public function getPrecipitationInMillimetres(): string
    {
        if (0 === $this->precipitationIntensity) {
            return '0 mm/uur';
        }

        return 10 * (($this->precipitationIntensity - 109) / 32).' mm/uur';
    }
}
