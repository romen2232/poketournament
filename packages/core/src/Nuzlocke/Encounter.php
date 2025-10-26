<?php

declare(strict_types=1);

namespace Core\Nuzlocke;

final class Encounter
{
    private string $status = 'CAPTURED';

    public function __construct(private readonly string $route, private readonly string $species)
    {
    }

    public function route(): string
    {
        return $this->route;
    }

    public function species(): string
    {
        return $this->species;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $allowed = ['CAPTURED', 'DEAD', 'MISSED', 'TRADED'];
        if (!in_array($status, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid status');
        }
        $this->status = $status;
    }
}
