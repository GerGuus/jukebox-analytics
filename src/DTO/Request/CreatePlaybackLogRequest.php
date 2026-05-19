<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePlaybackLogRequest
{
    #[Assert\NotNull]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public ?int $track_id = null;

    #[Assert\NotNull]
    #[Assert\Type('numeric')]
    #[Assert\GreaterThan(0)]
    public mixed $amount_paid = null;
}
