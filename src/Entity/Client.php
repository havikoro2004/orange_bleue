<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ClientRepository::class)]

class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 50)]
    #[Assert\Regex('/^\w+/')]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex('/^\w+/')]
    private ?string $short_desc = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $full_desc = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'client_logo', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: 'datetime' , nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(protocols: ['http', 'https', 'ftp'])]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex('/^\w+/')]
    private ?string $dpo = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email]
    private ?string $technical_contact = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email]
    private ?string $commercial_contact = null;

    #[ORM\ManyToMany(targetEntity: Permission::class, mappedBy: 'client')]
    private Collection $permissions;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Branch::class)]
    private Collection $branches;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createAt = null;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?User $user = null;
    public function __construct()
    {
        $this->permissions = new ArrayCollection();
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
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

    public function getTechnicalContact(): ?string
    {
        return $this->technical_contact;
    }

    public function setTechnicalContact(string $technical_contact): self
    {
        $this->technical_contact = $technical_contact;

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
     * @return Collection<int, Permission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->addClient($this);
        }

        return $this;
    }

    public function removePermission(Permission $permission): self
    {
        if ($this->permissions->removeElement($permission)) {
            $permission->removeClient($this);
        }

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

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

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

}
