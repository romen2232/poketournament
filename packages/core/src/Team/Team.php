<?php

declare(strict_types=1);

namespace Core\Team;

final class Team
{
    /** @var PokemonSet[] */
    private array $sets = [];

    public function __construct(private readonly string $name)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return PokemonSet[] */
    public function sets(): array
    {
        return $this->sets;
    }

    public function addSet(PokemonSet $set): void
    {
        if (count($this->sets) >= 6) {
            throw new \LogicException('Team full');
        }
        $this->sets[] = $set;
    }
}
