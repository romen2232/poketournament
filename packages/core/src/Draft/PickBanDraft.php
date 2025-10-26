<?php

declare(strict_types=1);

namespace Core\Draft;

final class PickBanDraft
{
    private string $state = 'BANNING';
    /** @var array<string, string[]> */
    private array $bans = ['P1' => [], 'P2' => []];
    /** @var array<string, string[]> */
    private array $picks = ['P1' => [], 'P2' => []];
    /** @var array<string, bool> */
    private array $confirmed = ['P1' => false, 'P2' => false];

    public function __construct(private readonly string $p1, private readonly string $p2)
    {
        $this->bans = [$p1 => [], $p2 => []];
        $this->picks = [$p1 => [], $p2 => []];
        $this->confirmed = [$p1 => false, $p2 => false];
    }

    public function state(): string
    {
        return $this->state;
    }

    /** @return array<string,string[]> */
    public function bans(): array
    {
        return $this->bans;
    }

    /** @return array<string,string[]> */
    public function picks(): array
    {
        return $this->picks;
    }

    public function ban(string $actor, string $pokemonUid): void
    {
        $this->assertActor($actor);
        if ($this->state !== 'BANNING') {
            throw new \LogicException('Not in banning state');
        }
        if (in_array($pokemonUid, $this->bans[$actor], true)) {
            throw new \LogicException('Duplicate ban');
        }
        $this->bans[$actor][] = $pokemonUid;
        if (count($this->bans[$this->p1]) >= 1 && count($this->bans[$this->p2]) >= 1) {
            $this->state = 'PICKING';
        }
    }

    public function pick(string $actor, string $pokemonUid): void
    {
        $this->assertActor($actor);
        if ($this->state !== 'PICKING') {
            throw new \LogicException('Not in picking state');
        }
        if (in_array($pokemonUid, $this->picks[$actor], true)) {
            throw new \LogicException('Duplicate pick');
        }
        $this->picks[$actor][] = $pokemonUid;
        if (count($this->picks[$this->p1]) >= 1 && count($this->picks[$this->p2]) >= 1) {
            $this->state = 'CONFIRM';
        }
    }

    public function confirm(string $actor): void
    {
        $this->assertActor($actor);
        if ($this->state !== 'CONFIRM') {
            throw new \LogicException('Not in confirm state');
        }
        $this->confirmed[$actor] = true;
        if ($this->confirmed[$this->p1] && $this->confirmed[$this->p2]) {
            $this->state = 'LOCKED';
        }
    }

    private function assertActor(string $actor): void
    {
        if ($actor !== $this->p1 && $actor !== $this->p2) {
            throw new \InvalidArgumentException('Unknown actor');
        }
    }
}
