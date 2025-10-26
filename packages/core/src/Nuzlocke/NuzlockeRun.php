<?php

declare(strict_types=1);

namespace Core\Nuzlocke;

final class NuzlockeRun
{
    /** @var Encounter[] */
    private array $encounters = [];

    public function __construct(private readonly string $title)
    {
    }

    public function title(): string
    {
        return $this->title;
    }

    /** @return Encounter[] */
    public function encounters(): array
    {
        return $this->encounters;
    }

    public function addEncounter(Encounter $encounter): void
    {
        $this->encounters[] = $encounter;
    }

    public function setStatus(string $route, string $status): void
    {
        foreach ($this->encounters as $enc) {
            if ($enc->route() === $route) {
                $enc->setStatus($status);
                return;
            }
        }
        throw new \InvalidArgumentException('Encounter not found');
    }
}
