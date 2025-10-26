<?php

declare(strict_types=1);

namespace Core\Team;

final class PokemonSet
{
    public function __construct(public readonly string $uid)
    {
    }
}
