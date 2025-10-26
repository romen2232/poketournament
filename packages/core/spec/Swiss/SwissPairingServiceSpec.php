<?php

namespace spec\Core\Swiss;

use Core\Swiss\SwissPairingService;
use PhpSpec\ObjectBehavior;

final class SwissPairingServiceSpec extends ObjectBehavior
{
    public function it_pairs_players_simple_even(): void
    {
        $players = ['A', 'B', 'C', 'D'];
        $scores = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        $this->beConstructedWith();

        $pairings = $this->pair($players, $scores, []);
        $pairings->shouldBeArray();
        $pairings->shouldHaveCount(2);
        $this->shouldSatisfy(function () use ($pairings) {
            foreach ($pairings->getWrappedObject() as $p) {
                if (!isset($p['p1']) || !isset($p['p2'])) {
                    throw new \RuntimeException('invalid pair');
                }
            }
        });
    }

    public function it_assigns_bye_on_odd_count(): void
    {
        $players = ['A', 'B', 'C'];
        $scores = ['A' => 0, 'B' => 0, 'C' => 0];
        $this->beConstructedWith();
        $result = $this->pair($players, $scores, ['byes' => []]);
        $result->shouldBeArray();
        $result->shouldHaveCount(2); // one pairing + one bye entry
        $this->shouldSatisfy(function () use ($result) {
            $found = false;
            foreach ($result->getWrappedObject() as $r) {
                if (isset($r['bye'])) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \RuntimeException('No BYE found');
            }
        });
    }

    public function it_avoids_rematches_and_pairs_across_groups_when_possible(): void
    {
        // Groups by score: [A(3), C(3)], [B(0), D(0)]
        // A already played C, so avoid A-C by pairing A-B and C-D.
        $players = ['A', 'B', 'C', 'D'];
        $scores = ['A' => 3, 'B' => 0, 'C' => 3, 'D' => 0];
        $opponents = [
            'A' => ['C'],
            'C' => ['A'],
        ];

        $this->beConstructedWith();
        $pairs = $this->pair($players, $scores, ['opponents' => $opponents]);
        $pairs->shouldBeArray();
        $pairs->shouldHaveCount(2);
        // Expect cross-pairing: A-B and C-D (order not guaranteed)
        $this->shouldSatisfy(function () use ($pairs) {
            $norm = [];
            foreach ($pairs->getWrappedObject() as $p) {
                if (isset($p['bye'])) {
                    continue;
                }
                $norm[] = [min($p['p1'], $p['p2']), max($p['p1'], $p['p2'])];
            }
            $need = [[ 'A', 'B' ], [ 'C', 'D' ]];
            foreach ($need as $expect) {
                $ok = false;
                foreach ($norm as $n) {
                    if ($n === $expect) {
                        $ok = true;
                        break;
                    }
                }
                if (!$ok) {
                    throw new \RuntimeException('Expected pair missing');
                }
            }
        });
    }

    public function it_assigns_bye_to_lowest_score_eligible_player(): void
    {
        $players = ['A', 'B', 'C'];
        $scores = ['A' => 3, 'B' => 0, 'C' => 0];
        // C already had a bye; pick B
        $byes = ['C'];
        $this->beConstructedWith();
        $pairs = $this->pair($players, $scores, ['byes' => $byes]);
        $pairs->shouldBeArray();
        $this->shouldSatisfy(function () use ($pairs) {
            $pickedB = false;
            foreach ($pairs->getWrappedObject() as $p) {
                if (isset($p['bye']) && $p['bye'] === 'B') {
                    $pickedB = true;
                    break;
                }
            }
            if (!$pickedB) {
                throw new \RuntimeException('BYE not assigned to B');
            }
        });
    }
}
