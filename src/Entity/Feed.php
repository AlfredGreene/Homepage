<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedRepository")
 */
class Feed
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $feedUrl;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $feedIcon;

    /**
     * @ORM\Column(type="boolean")
     */
    private $autoPin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FeedItem", mappedBy="feedId", orphanRemoval=true)
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId()
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

    public function getFeedUrl(): ?string
    {
        return $this->feedUrl;
    }

    public function setFeedUrl(string $feedUrl): self
    {
        $this->feedUrl = $feedUrl;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getFeedIcon(): ?string
    {
        return $this->feedIcon;
    }

    public function setFeedIcon(?string $feedIcon): self
    {
        $this->feedIcon = $feedIcon;

        return $this;
    }

    public function getAutoPin(): ?bool
    {
        return $this->autoPin;
    }

    public function setAutoPin(bool $autoPin): self
    {
        $this->autoPin = $autoPin;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|FeedItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItems(FeedItem $pinned): self
    {
        if (!$this->items->contains($pinned)) {
            $this->items[] = $pinned;
            $pinned->setFeedId($this);
        }

        return $this;
    }

    public function removeItems(FeedItem $pinned): self
    {
        if ($this->items->contains($pinned)) {
            $this->items->removeElement($pinned);
            // set the owning side to null (unless already changed)
            if ($pinned->getFeedId() === $this) {
                $pinned->setFeedId(null);
            }
        }

        return $this;
    }
}
