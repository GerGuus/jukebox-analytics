<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $artist = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    /**
     * @var Collection<int, PlaybackLog>
     */
    #[ORM\OneToMany(targetEntity: PlaybackLog::class, mappedBy: 'track')]
    private Collection $playbackLogs;

    public function __construct()
    {
        $this->playbackLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, PlaybackLog>
     */
    public function getPlaybackLogs(): Collection
    {
        return $this->playbackLogs;
    }

    public function addPlaybackLog(PlaybackLog $playbackLog): static
    {
        if (!$this->playbackLogs->contains($playbackLog)) {
            $this->playbackLogs->add($playbackLog);
            $playbackLog->setTrack($this);
        }

        return $this;
    }

    public function removePlaybackLog(PlaybackLog $playbackLog): static
    {
        if ($this->playbackLogs->removeElement($playbackLog)) {
            // set the owning side to null (unless already changed)
            if ($playbackLog->getTrack() === $this) {
                $playbackLog->setTrack(null);
            }
        }

        return $this;
    }
}
