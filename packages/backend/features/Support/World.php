<?php

namespace Support;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class World
{
    public static ?int $lastStatus = null;
    public static ?array $lastJson = null;
    public static ?HttpClientInterface $http = null;
}
