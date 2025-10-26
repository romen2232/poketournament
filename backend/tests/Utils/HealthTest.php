<?php

namespace App\Tests\Utils;

use App\Utils\Health;
use PHPUnit\Framework\TestCase;

final class HealthTest extends TestCase
{
    public function testStatusIsOk(): void
    {
        $health = new Health();
        $this->assertSame('ok', $health->status());
    }
}
