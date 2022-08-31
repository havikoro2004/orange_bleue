<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $active = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $short_desc = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $full_desc = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo_url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dpo = null;

    #[ORM\Column(length: 255)]
    private ?string $techno_contact = null;

    #[ORM\Column(length: 255)]
    private ?string $commercial_contact = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Branch::class, orphanRemoval: true)]
    private Collection $branches;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?Permission $permission = null;

    public function __construct()
    {
        $this->branches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getShortDesc(): ?string
    {
        return $this->short_desc;
    }

    public function setShortDesc(?string $short_desc): self
    {
        $this->short_desc = $short_desc;

        return $this;
    }

    public function getFullDesc(): ?string
    {
        return $this->full_desc;
    }

    public function setFullDesc(?string $full_desc): self
    {
        $this->full_desc = $full_desc;

        return $this;
    }

    public function getLogoUrl(): ?string
    {
        return $this->logo_url;
    }

    public function setLogoUrl(?string $logo_url): self
    {
        $this->logo_url = $logo_url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDpo(): ?string
    {
        return $this->dpo;
    }

    public function setDpo(?string $dpo): self
    {
        $this->dpo = $dpo;

        return $this;
    }

    public function getTechnoContact(): ?string
    {
        return $this->techno_contact;
    }

    public function setTechnoContact(string $techno_contact): self
    {
        $this->techno_contact = $techno_contact;

        return $this;
    }

    public function getCommercialContact(): ?string
    {
        return $this->commercial_contact;
    }

    public function setCommercialContact(string $commercial_contact): self
    {
        $this->commercial_contact = $commercial_contact;

        return $this;
    }

    /**
     * @return Collection<int, Branch>
     */
    public function getBranches(): Collection
    {
        return $this->branches;
    }

    public function addBranch(Branch $branch): self
    {
        if (!$this->branches->contains($branch)) {
            $this->branches->add($branch);
            $branch->setClient($this);
        }

        return $this;
    }

    public function removeBranch(Branch $branch): self
    {
        if ($this->branches->removeElement($branch)) {
            // set the owning side to null (unless already changed)
            if ($branch->getClient() === $this) {
                $branch->setClient(null);
            }
        }

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
            $this->user->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getClient() !== $this) {
            $user->setClient($this);
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
        if ($permission->getClient() !== $this) {
            $permission->setClient($this);
        }

        $this->permission = $permission;

        return $this;
    }
}
