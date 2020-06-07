<?php

namespace app\Entities\Words;

class Word
{
  /** @var string */
  protected $title;
  /** @var string */
  protected $image;
  /** @var string */
  protected $audio;

  public function __construct(string $title, string $image, string $audio)
  {
    $this->title = $title;
    $this->image = $image;
    $this->audio = $audio;
  }

  public function jsonSerialize()
  {
    return [
      'title' => $this->title,
      'image' => $this->image,
      'audio' => $this->audio
    ];
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setImage(string $image): void
  {
    $this->image = $image;
  }

  public function getImage(): string
  {
    return $this->image;
  }

  public function setAudio(string $audio): void
  {
    $this->audio = $audio;
  }

  public function getAudio(): string
  {
    return $this->audio;
  }
}