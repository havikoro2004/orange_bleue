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

    #[ORM\Column]
    private ?bool $read_resa = null;

    #[ORM\Column]
    private ?bool $edit_resa = null;

    #[ORM\Column]
    private ?bool $remove_resa = null;

    #[ORM\Column]
    private ?bool $read_payment = null;

    #[ORM\Column]
    private ?bool $edit_payment = null;

    #[ORM\Column]
    private ?bool $manage_drink = null;

    #[ORM\Column]
    private ?bool $add_sub = null;

    #[ORM\Column]
    private ?bool $edit_sub = null;

    #[ORM\Column]
    private ?bool $remove_sub = null;

    #[ORM\Column]
    private ?bool $manage_schedules = null;

    #[ORM\ManyToMany(targetEntity: Client::class, inversedBy: 'permissions')]
    private Collection $client;

    #[ORM\OneToMany(mappedBy: 'permission', targetEntity: Branch::class)]
    private Collection $branches;

    public function __construct()
    {
        $this->client = new ArrayCollection();
        $this->branches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isReadResa(): ?bool
    {
        return $this->read_resa;
    }

    public function setReadResa(bool $read_resa): self
    {
        $this->read_resa = $read_resa;

        return $this;
    }

    public function isEditResa(): ?bool
    {
        return $this->edit_resa;
    }

    public function setEditResa(bool $edit_resa): self
    {
        $this->edit_resa = $edit_resa;

        return $this;
    }

    public function isRemoveResa(): ?bool
    {
        return $this->remove_resa;
    }

    public function setRemoveResa(bool $remove_resa): self
    {
        $this->remove_resa = $remove_resa;

        return $this;
    }

    public function isReadPayment(): ?bool
    {
        return $this->read_payment;
    }

    public function setReadPayment(bool $read_payment): self
    {
        $this->read_payment = $read_payment;

        return $this;
    }

    public function isEditPayment(): ?bool
    {
        return $this->edit_payment;
    }

    public function setEditPayment(bool $edit_payment): self
    {
        $this->edit_payment = $edit_payment;

        return $this;
    }

    public function isManageDrink(): ?bool
    {
        return $this->manage_drink;
    }

    public function setManageDrink(bool $manage_drink): self
    {
        $this->manage_drink = $manage_drink;

        return $this;
    }

    public function isAddSub(): ?bool
    {
        return $this->add_sub;
    }

    public function setAddSub(bool $add_sub): self
    {
        $this->add_sub = $add_sub;

        return $this;
    }

    public function isEditSub(): ?bool
    {
        return $this->edit_sub;
    }

    public function setEditSub(bool $edit_sub): self
    {
        $this->edit_sub = $edit_sub;

        return $this;
    }

    public function isRemoveSub(): ?bool
    {
        return $this->remove_sub;
    }

    public function setRemoveSub(bool $remove_sub): self
    {
        $this->remove_sub = $remove_sub;

        return $this;
    }

    public function isManageSchedules(): ?bool
    {
        return $this->manage_schedules;
    }

    public function setManageSchedules(bool $manage_schedules): self
    {
        $this->manage_schedules = $manage_schedules;

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
    public function getBranches(): Collection
    {
        return $this->branches;
    }

    public function addBranch(Branch $branch): self
    {
        if (!$this->branches->contains($branch)) {
            $this->branches->add($branch);
            $branch->setPermission($this);
        }

        return $this;
    }

    public function removeBranch(Branch $branch): self
    {
        if ($this->branches->removeElement($branch)) {
            // set the owning side to null (unless already changed)
            if ($branch->getPermission() === $this) {
                $branch->setPermission(null);
            }
        }

        return $this;
    }
}
