<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Infrastructure\GraphQL\SchemaFactory;
use GraphQL\GraphQL;
use GraphQL\Error\DebugFlag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GraphQLController
{
    public function __construct(private readonly SchemaFactory $schemaFactory)
    {
    }

    #[Route(path: '/graphql', name: 'graphql', methods: ['POST'])]
    public function handle(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true) ?: [];
        $query = $payload['query'] ?? '';
        $variables = $payload['variables'] ?? [];
        $schema = $this->schemaFactory->create();
        $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
        $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);
        return new JsonResponse($output);
    }
}
