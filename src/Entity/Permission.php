<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $members_read = null;

    #[ORM\Column]
    private ?bool $members_write = null;

    #[ORM\Column]
    private ?bool $members_add = null;

    #[ORM\Column]
    private ?bool $members_products_add = null;

    #[ORM\Column]
    private ?bool $members_payement_schedules_read = null;

    #[ORM\Column]
    private ?bool $members_statistic_read = null;

    #[ORM\Column]
    private ?bool $members_subscription_read = null;

    #[ORM\Column]
    private ?bool $members_schedules_write = null;

    #[ORM\Column]
    private ?bool $payement_schedules_read = null;

    #[ORM\Column]
    private ?bool $payement_schedules_write = null;

    #[ORM\Column]
    private ?bool $payement_day_read = null;

    #[ORM\OneToOne(inversedBy: 'permission', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToOne(inversedBy: 'permission', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Branch $branch = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isMembersRead(): ?bool
    {
        return $this->members_read;
    }

    public function setMembersRead(bool $members_read): self
    {
        $this->members_read = $members_read;

        return $this;
    }

    public function isMembersWrite(): ?bool
    {
        return $this->members_write;
    }

    public function setMembersWrite(bool $members_write): self
    {
        $this->members_write = $members_write;

        return $this;
    }

    public function isMembersAdd(): ?bool
    {
        return $this->members_add;
    }

    public function setMembersAdd(bool $members_add): self
    {
        $this->members_add = $members_add;

        return $this;
    }

    public function isMembersProductsAdd(): ?bool
    {
        return $this->members_products_add;
    }

    public function setMembersProductsAdd(bool $members_products_add): self
    {
        $this->members_products_add = $members_products_add;

        return $this;
    }

    public function isMembersPayementSchedulesRead(): ?bool
    {
        return $this->members_payement_schedules_read;
    }

    public function setMembersPayementSchedulesRead(bool $members_payement_schedules_read): self
    {
        $this->members_payement_schedules_read = $members_payement_schedules_read;

        return $this;
    }

    public function isMembersStatisticRead(): ?bool
    {
        return $this->members_statistic_read;
    }

    public function setMembersStatisticRead(bool $members_statistic_read): self
    {
        $this->members_statistic_read = $members_statistic_read;

        return $this;
    }

    public function isMembersSubscriptionRead(): ?bool
    {
        return $this->members_subscription_read;
    }

    public function setMembersSubscriptionRead(bool $members_subscription_read): self
    {
        $this->members_subscription_read = $members_subscription_read;

        return $this;
    }

    public function isMembersSchedulesWrite(): ?bool
    {
        return $this->members_schedules_write;
    }

    public function setMembersSchedulesWrite(bool $members_schedules_write): self
    {
        $this->members_schedules_write = $members_schedules_write;

        return $this;
    }

    public function isPayementSchedulesRead(): ?bool
    {
        return $this->payement_schedules_read;
    }

    public function setPayementSchedulesRead(bool $payement_schedules_read): self
    {
        $this->payement_schedules_read = $payement_schedules_read;

        return $this;
    }

    public function isPayementSchedulesWrite(): ?bool
    {
        return $this->payement_schedules_write;
    }

    public function setPayementSchedulesWrite(bool $payement_schedules_write): self
    {
        $this->payement_schedules_write = $payement_schedules_write;

        return $this;
    }

    public function isPayementDayRead(): ?bool
    {
        return $this->payement_day_read;
    }

    public function setPayementDayRead(bool $payement_day_read): self
    {
        $this->payement_day_read = $payement_day_read;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }
}
