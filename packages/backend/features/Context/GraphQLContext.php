<?php

namespace Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Support\World;
use Symfony\Component\HttpClient\HttpClient;

final class GraphQLContext implements Context
{
    /** @var string[] */
    private array $players = [];
    /** @var array<string,int> */
    private array $scores = [];
    /** @var array<string,string[]> */
    private array $opponents = [];
    /** @var array<int,array{p1?:string,p2?:string,winner?:string,bye?:string}> */
    private array $matches = [];
    /** @var array<string,int> */
    private array $randKey = [];
    private ?string $tournamentId = null;

    public function __construct()
    {
        if (World::$http === null) {
            World::$http = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        }
    }

    /**
     * @Given the following players:
     */
    public function theFollowingPlayers(TableNode $table): void
    {
        $this->players = array_map(fn ($r) => (string)$r['player'], $table->getHash());
    }

    /**
     * @Given the following scores:
     */
    public function theFollowingScores(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $this->scores[$row['player']] = (int)$row['score'];
        }
    }

    /**
     * @Given the following previous opponents:
     */
    public function theFollowingOpponents(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $ops = array_values(array_filter(array_map('trim', explode(',', (string)$row['opponents']))));
            $this->opponents[$row['player']] = $ops;
        }
    }

    /**
     * @When I query pairings
     */
    public function iQueryPairings(): void
    {
        $scoresList = [];
        foreach ($this->scores as $p => $s) {
            $scoresList[] = ['player' => $p, 'score' => $s];
        }
        $opponentsList = [];
        foreach ($this->opponents as $p => $ops) {
            $opponentsList[] = ['player' => $p, 'opponents' => $ops];
        }
        $payload = [
            'query' => 'query P($players:[String!]!, $scores:[ScoreInput!]!, $opponents:[OpponentInput!]){ pair(players:$players, scores:$scores, opponents:$opponents){ p1 p2 bye } }',
            'variables' => [
                'players' => $this->players,
                'scores' => $scoresList,
                'opponents' => $opponentsList,
            ],
        ];
        $this->postGraphQL($payload);
    }

    /**
     * @Given the following matches:
     */
    public function theFollowingMatches(TableNode $table): void
    {
        $this->matches = [];
        foreach ($table->getHash() as $row) {
            $rec = [];
            if (!empty($row['p1'])) {
                $rec['p1'] = $row['p1'];
            }
            if (!empty($row['p2'])) {
                $rec['p2'] = $row['p2'];
            }
            if (!empty($row['winner'])) {
                $rec['winner'] = $row['winner'];
            }
            if (!empty($row['bye'])) {
                $rec['bye'] = $row['bye'];
            }
            $this->matches[] = $rec;
        }
    }

    /**
     * @Given the following random key:
     */
    public function theFollowingRandomKey(TableNode $table): void
    {
        $this->randKey = [];
        foreach ($table->getHash() as $row) {
            $this->randKey[$row['player']] = (int)$row['key'];
        }
    }

    /**
     * @When I query standings
     */
    public function iQueryStandings(): void
    {
        $randKeyList = [];
        foreach ($this->randKey as $p => $k) {
            $randKeyList[] = ['player' => $p, 'key' => $k];
        }
        $payload = [
            'query' => 'query S($matches:[MatchInput!]!, $randKey:[RandKeyInput!]){ standings(matches:$matches, randKey:$randKey){ player points } }',
            'variables' => [
                'matches' => $this->matches,
                'randKey' => $randKeyList,
            ],
        ];
        $this->postGraphQL($payload);
    }

    /**
     * @Given tournament id :id
     */
    public function tournamentId(string $id): void
    {
        $this->tournamentId = $id;
    }

    /**
     * @When I open round :round
     */
    public function iOpenRound(int $round): void
    {
        $payload = [
            'query' => 'mutation O($tid:String!, $r:Int!){ openRound(tournamentId:$tid, round:$r){ tournamentId round status } }',
            'variables' => [ 'tid' => $this->tournamentId ?? 'T1', 'r' => $round ],
        ];
        $this->postGraphQL($payload);
    }

    /**
     * @When I report result :p1 vs :p2 winner :winner in round :round
     */
    public function iReportResult(string $p1, string $p2, string $winner, int $round): void
    {
        $payload = [
            'query' => 'mutation R($tid:String!, $r:Int!, $p1:String!, $p2:String!, $w:String!){ reportResult(tournamentId:$tid, round:$r, p1:$p1, p2:$p2, winner:$w){ tournamentId round p1 p2 winner status } }',
            'variables' => [ 'tid' => $this->tournamentId ?? 'T1', 'r' => $round, 'p1' => $p1, 'p2' => $p2, 'w' => $winner ],
        ];
        $this->postGraphQL($payload);
    }

    /**
     * @param array<string,mixed> $payload
     */
    private function postGraphQL(array $payload): void
    {
        $resp = World::$http->request('POST', 'https://nginx/graphql', [
            'headers' => ['Content-Type' => 'application/json', 'Host' => 'api.poketournament.local'],
            'body' => json_encode($payload),
            'max_redirects' => 0,
        ]);
        World::$lastStatus = $resp->getStatusCode();
        $text = $resp->getContent(false);
        World::$lastJson = json_decode($text, true);
    }
}
