<?php

declare(strict_types=1);

namespace Core\Swiss;

final class StandingsCalculator
{
    /**
     * @param array<int, array{p1?:string,p2?:string,winner?:string,bye?:string}> $matches
     * @param array<string,int> $randKey
     * @return array<int, array{player:string, points:int, oppWinPct:float, directWins:int, randKey:int}>
     */
    public function compute(array $matches, array $randKey): array
    {
        $players = [];
        $points = [];
        $opponents = [];
        $wins = [];

        foreach ($matches as $m) {
            if (isset($m['bye'])) {
                $p = $m['bye'];
                $players[$p] = true;
                $points[$p] = ($points[$p] ?? 0) + 3; // BYE win
                continue;
            }
            $p1 = (string)($m['p1'] ?? '');
            $p2 = (string)($m['p2'] ?? '');
            if ($p1 === '' || $p2 === '') {
                continue;
            }
            $players[$p1] = true;
            $players[$p2] = true;
            $opponents[$p1][] = $p2;
            $opponents[$p2][] = $p1;
            if (isset($m['winner'])) {
                $w = $m['winner'];
                $points[$w] = ($points[$w] ?? 0) + 3;
                $wins[$w][] = $w === $p1 ? $p2 : $p1;
            }
        }

        // Compute win percentage for each player (treat BYE as neutral: exclude BYE from opp list)
        $winPct = [];
        foreach (array_keys($players) as $p) {
            $oppList = $opponents[$p] ?? [];
            $oppWinsPct = [];
            foreach ($oppList as $opp) {
                $oppPts = $points[$opp] ?? 0;
                // approximate win % by points / (3 * matches played)
                $matchesPlayed = count($opponents[$opp] ?? []);
                $den = max(1, $matchesPlayed * 3);
                $oppWinsPct[] = ($oppPts) / $den;
            }
            $winPct[$p] = empty($oppWinsPct) ? 0.0 : array_sum($oppWinsPct) / count($oppWinsPct);
        }

        // Direct wins metric
        $directWins = [];
        foreach (array_keys($players) as $p) {
            $directWins[$p] = count($wins[$p] ?? []);
        }

        $rows = [];
        foreach (array_keys($players) as $p) {
            $rows[] = [
                'player' => $p,
                'points' => $points[$p] ?? 0,
                'oppWinPct' => round($winPct[$p] ?? 0.0, 4),
                'directWins' => $directWins[$p] ?? 0,
                'randKey' => $randKey[$p] ?? 0,
            ];
        }

        usort($rows, function ($a, $b) {
            if ($a['points'] !== $b['points']) {
                return $b['points'] <=> $a['points'];
            }
            if ($a['oppWinPct'] !== $b['oppWinPct']) {
                return $b['oppWinPct'] <=> $a['oppWinPct'];
            }
            if ($a['directWins'] !== $b['directWins']) {
                return $b['directWins'] <=> $a['directWins'];
            }
            return $a['randKey'] <=> $b['randKey'];
        });

        return $rows;
    }
}
