<?php

namespace Context;

use Behat\Behat\Context\Context;
use Support\World;
use Symfony\Component\HttpClient\HttpClient;

final class RequestContext implements Context
{
    public function __construct()
    {
        if (World::$http === null) {
            World::$http = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        }
    }

    /**
     * @When I POST to :path with JSON body:
     */
    public function iPostToWithJsonBody(string $path, string $body): void
    {
        $base = 'https://nginx';
        $resp = World::$http->request('POST', $base . $path, [
            'headers' => ['Content-Type' => 'application/json', 'Host' => 'api.poketournament.local'],
            'max_redirects' => 0,
            'body' => $body,
        ]);
        World::$lastStatus = $resp->getStatusCode();
        $text = $resp->getContent(false);
        World::$lastJson = json_decode($text, true);
    }
}
