<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Client::class, inversedBy: 'permissions')]
    private Collection $client;

    #[ORM\ManyToMany(targetEntity: Branch::class, inversedBy: 'permissions')]
    private Collection $branch;

    public function __construct()
    {
        $this->client = new ArrayCollection();
        $this->branch = new ArrayCollection();
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

    /**
     * @return Collection<int, Client>
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(Client $client): self
    {
        if (!$this->client->contains($client)) {
            $this->client->add($client);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        $this->client->removeElement($client);

        return $this;
    }

    /**
     * @return Collection<int, Branch>
     */
    public function getBranch(): Collection
    {
        return $this->branch;
    }

    public function addBranch(Branch $branch): self
    {
        if (!$this->branch->contains($branch)) {
            $this->branch->add($branch);
        }

        return $this;
    }

    public function removeBranch(Branch $branch): self
    {
        $this->branch->removeElement($branch);

        return $this;
    }
}
