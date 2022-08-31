<?php

namespace App\Entity;

use App\Repository\BranchRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BranchRepository::class)]
class Branch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    private ?string $active = null;

    #[ORM\ManyToOne(inversedBy: 'branches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToOne(mappedBy: 'branch', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'branch', cascade: ['persist', 'remove'])]
    private ?Permission $permission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getActive(): ?string
    {
        return $this->active;
    }

    public function setActive(string $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setBranch(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getBranch() !== $this) {
            $user->setBranch($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getPermission(): ?Permission
    {
        return $this->permission;
    }

    public function setPermission(Permission $permission): self
    {
        // set the owning side of the relation if necessary
        if ($permission->getBranch() !== $this) {
            $permission->setBranch($this);
        }

        $this->permission = $permission;

        return $this;
    }
}
