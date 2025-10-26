<?php

declare(strict_types=1);

namespace Core\Swiss;

class SwissPairingService
{
    /**
     * @param string[] $players
     * @param array<string,int> $scores
     * @param array{byes?: string[], opponents?: array<string,string[]>} $options
     * @return array<int, array{p1:string,p2?:string,bye?:string}>
     */
    public function pair(array $players, array $scores, array $options = []): array
    {
        $byesGiven = $options['byes'] ?? [];
        $opponents = $options['opponents'] ?? [];

        // Determine BYE if odd count
        $byeEntry = null;
        if ((count($players) % 2) === 1) {
            $eligible = array_values(array_filter($players, function (string $p) use ($byesGiven) {
                return !in_array($p, $byesGiven, true);
            }));
            // If no eligible (all had BYE), fall back to everyone
            if (empty($eligible)) {
                $eligible = $players;
            }
            // Pick lowest score among eligible, tie-break by natural order
            usort($eligible, function (string $a, string $b) use ($scores) {
                $sa = $scores[$a] ?? 0;
                $sb = $scores[$b] ?? 0;
                if ($sa === $sb) {
                    return strcmp($a, $b);
                }
                return $sa <=> $sb; // ascending (lowest first)
            });
            $byePlayer = $eligible[0];
            // remove from players
            $players = array_values(array_filter($players, fn (string $p) => $p !== $byePlayer));
            $byeEntry = ['bye' => $byePlayer];
        }

        // Group players by score (descending groups)
        $groups = [];
        foreach ($players as $p) {
            $s = $scores[$p] ?? 0;
            $groups[$s][] = $p;
        }
        krsort($groups); // high to low
        foreach ($groups as &$g) {
            sort($g);
        } // deterministic order
        unset($g);

        $pairs = [];
        $carry = [];
        $groupScores = array_keys($groups);
        for ($i = 0; $i < count($groupScores); $i++) {
            $score = $groupScores[$i];
            $current = array_merge($carry, $groups[$score]);
            $carry = [];
            // Greedy pairing avoiding rematches when possible
            $used = [];
            while (count($current) - count($used) >= 2) {
                $p1 = null;
                foreach ($current as $c) {
                    if (!in_array($c, $used, true)) {
                        $p1 = $c;
                        break;
                    }
                }
                if ($p1 === null) {
                    break;
                }
                // find partner not a rematch
                $partner = null;
                $partnerIdx = null;
                foreach ($current as $idx => $cand) {
                    if ($cand === $p1 || in_array($cand, $used, true)) {
                        continue;
                    }
                    $played = $opponents[$p1] ?? [];
                    if (!in_array($cand, $played, true)) {
                        $partner = $cand;
                        $partnerIdx = $idx;
                        break;
                    }
                }
                // if none found, take first available (rematch unavoidable)
                if ($partner === null) {
                    foreach ($current as $idx => $cand) {
                        if ($cand !== $p1 && !in_array($cand, $used, true)) {
                            $partner = $cand;
                            $partnerIdx = $idx;
                            break;
                        }
                    }
                }
                if ($partner === null) {
                    break;
                }
                $pairs[] = ['p1' => $p1, 'p2' => $partner];
                $used[] = $p1;
                $used[] = $partner;
            }
            // Leftovers float to next group
            foreach ($current as $c) {
                if (!in_array($c, $used, true)) {
                    $carry[] = $c;
                }
            }
        }

        // If anything remains unpaired, pair within leftovers
        if (count($carry) >= 2) {
            sort($carry);
            while (count($carry) >= 2) {
                $p1 = array_shift($carry);
                $p2 = array_shift($carry);
                $pairs[] = ['p1' => $p1, 'p2' => $p2];
            }
        }
        // If one leftover despite earlier BYE, assign BYE now
        if (count($carry) === 1) {
            $byeEntry = ['bye' => $carry[0]];
        }

        if ($byeEntry !== null) {
            $pairs[] = $byeEntry;
        }
        return $pairs;
    }
}
