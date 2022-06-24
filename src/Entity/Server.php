<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
class Server
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $openDay;

    #[ORM\Column(type: 'boolean')]
    private $wipe;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $wipe_date;

    #[ORM\Column(type: 'boolean')]
    private $type;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $clan_size;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $discord;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'servers')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_owner;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: Review::class, orphanRemoval: true)]
    private $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOpenDay(): ?\DateTimeImmutable
    {
        return $this->openDay;
    }

    public function setOpenDay(\DateTimeImmutable $openDay): self
    {
        $this->openDay = $openDay;

        return $this;
    }

    public function isWipe(): ?bool
    {
        return $this->wipe;
    }

    public function setWipe(bool $wipe): self
    {
        $this->wipe = $wipe;

        return $this;
    }

    public function getWipeDate(): ?\DateTimeImmutable
    {
        return $this->wipe_date;
    }

    public function setWipeDate(\DateTimeImmutable $wipe_date): self
    {
        $this->wipe_date = $wipe_date;

        return $this;
    }

    public function isType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getClanSize(): ?int
    {
        return $this->clan_size;
    }

    public function setClanSize(int $clan_size): self
    {
        $this->clan_size = $clan_size;

        return $this;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(?string $discord): self
    {
        $this->discord = $discord;

        return $this;
    }

    public function getUserOwner(): ?User
    {
        return $this->user_owner;
    }

    public function setUserOwner(?User $user_owner): self
    {
        $this->user_owner = $user_owner;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setServer($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getServer() === $this) {
                $review->setServer(null);
            }
        }

        return $this;
    }
}
