<?php

namespace Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Support\World;

final class JsonContentContext implements Context
{
    /**
     * @Then the JSON array at path :path should contain pairs:
     */
    public function jsonArrayShouldContainPairs(string $path, TableNode $table): void
    {
        $arr = $this->getArrayAtPath($path);
        $have = [];
        foreach ($arr as $item) {
            if (!is_array($item)) {
                continue;
            }
            $p1 = $item['p1'] ?? null;
            $p2 = $item['p2'] ?? null;
            if ($p1 !== null && $p2 !== null) {
                $pair = [min($p1, $p2), max($p1, $p2)];
                $have[] = $pair;
            }
        }
        foreach ($table->getHash() as $row) {
            $exp = [min($row['p1'], $row['p2']), max($row['p1'], $row['p2'])];
            $found = false;
            foreach ($have as $h) {
                if ($h === $exp) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \RuntimeException('Expected pair not found: ' . implode('-', $exp));
            }
        }
    }

    /**
     * @Then the JSON array at path :path should contain a bye for :player
     */
    public function jsonArrayShouldContainByeFor(string $path, string $player): void
    {
        $arr = $this->getArrayAtPath($path);
        foreach ($arr as $item) {
            if (is_array($item) && ($item['bye'] ?? null) === $player) {
                return;
            }
        }
        throw new \RuntimeException('Expected bye for ' . $player);
    }

    /**
     * @Then the JSON array at path :path should contain players with points:
     */
    public function jsonArrayShouldContainPlayersWithPoints(string $path, TableNode $table): void
    {
        $arr = $this->getArrayAtPath($path);
        foreach ($table->getHash() as $row) {
            $player = $row['player'];
            $points = (int)$row['points'];
            $found = false;
            foreach ($arr as $item) {
                if (!is_array($item)) {
                    continue;
                }
                if (($item['player'] ?? null) === $player && (int)($item['points'] ?? -1) === $points) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \RuntimeException('Expected standing not found for ' . $player . ' with ' . $points . ' points');
            }
        }
    }

    /**
     * @Then the JSON array at path :path should have players in order:
     */
    public function jsonArrayShouldHavePlayersInOrder(string $path, TableNode $table): void
    {
        $arr = $this->getArrayAtPath($path);
        $expected = array_map(fn ($r) => $r['player'], $table->getHash());
        $got = [];
        foreach ($arr as $item) {
            if (is_array($item) && isset($item['player'])) {
                $got[] = $item['player'];
            }
        }
        $slice = array_slice($got, 0, count($expected));
        if ($slice !== $expected) {
            throw new \RuntimeException('Player order mismatch: got [' . implode(',', $slice) . '] expected [' . implode(',', $expected) . ']');
        }
    }

    /** @return array<int,mixed> */
    private function getArrayAtPath(string $path): array
    {
        $parts = explode('.', $path);
        $node = World::$lastJson;
        foreach ($parts as $p) {
            if (!is_array($node) || !array_key_exists($p, $node)) {
                throw new \RuntimeException('Path not found: ' . $path);
            }
            $node = $node[$p];
        }
        if (!is_array($node)) {
            throw new \RuntimeException('Expected array at path: ' . $path);
        }
        return $node;
    }
}
