<?php

namespace Context;

use Behat\Behat\Context\Context;
use Support\World;

final class HttpContext implements Context
{
    /**
     * @Then the response code should be :code
     */
    public function theResponseCodeShouldBe(int $code): void
    {
        if (World::$lastStatus !== $code) {
            throw new \RuntimeException('Expected ' . $code . ' got ' . (World::$lastStatus ?? -1));
        }
    }

    /**
     * @Then the JSON at path :path should be an array
     */
    public function theJsonAtPathShouldBeAnArray(string $path): void
    {
        $node = $this->getJsonPath($path);
        if (!is_array($node)) {
            throw new \RuntimeException('Expected array at ' . $path);
        }
    }

    private function getJsonPath(string $path)
    {
        $parts = explode('.', $path);
        $node = World::$lastJson;
        foreach ($parts as $p) {
            if (!is_array($node) || !array_key_exists($p, $node)) {
                return null;
            }
            $node = $node[$p];
        }
        return $node;
    }
}
