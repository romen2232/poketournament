<?php

declare(strict_types=1);

namespace App\Application\Swiss;

use Core\Swiss\SwissPairingService as CoreSwissPairingService;

final class PairingService
{
    public function __construct(private readonly CoreSwissPairingService $core)
    {
    }

    /**
     * @param string[] $players
     * @param array<string,int> $scores
     * @param array{byes?: string[]} $options
     * @return array<int, array{p1:string,p2?:string,bye?:string}>
     */
    public function generate(array $players, array $scores, array $options = []): array
    {
        return $this->core->pair($players, $scores, $options);
    }
}

