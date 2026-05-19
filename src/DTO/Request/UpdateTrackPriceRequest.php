<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTrackPriceRequest
{
    #[Assert\NotNull]
    #[Assert\Type('numeric')]
    #[Assert\GreaterThan(0)]
    public mixed $new_price = null;
}
