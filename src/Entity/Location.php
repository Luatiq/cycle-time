<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;

// @TODO permissions
#[ApiResource]
#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $display = null;

    // Ensure that latitude is in NL/BE
    #[LessThan(53.55826138253766)]
    #[GreaterThan(49.4956686976582)]
    #[ORM\Column]
    private ?float $latitude = null;

    // Ensure that longitude is in NL/BE
    #[LessThan(7.066177562656723)]
    #[GreaterThan(2.544808438961323)]
    #[ORM\Column]
    private ?float $longitude = null;

    #[Ignore]
    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Blameable(on: 'create')]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(string $display): static
    {
        $this->display = $display;

        return $this;
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

    #[Ignore]
    public function getUser(): ?User
    {
        return $this->User;
    }

    #[Ignore]
    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
