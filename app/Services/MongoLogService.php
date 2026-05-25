<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MongoLogService
{
    public function record(string $collection, array $payload): void
    {
        $payload = array_merge($payload, [
            'collection' => $collection,
            'recorded_at' => now()->toISOString(),
        ]);

        if (class_exists(\MongoDB\Client::class)) {
            try {
                $client = new \MongoDB\Client(config('mongodb.uri'));
                $client
                    ->selectDatabase(config('mongodb.database'))
                    ->selectCollection(config("mongodb.collections.$collection", $collection))
                    ->insertOne($payload);

                return;
            } catch (\Throwable $exception) {
                Log::warning('MongoDB write failed, falling back to Laravel log.', [
                    'collection' => $collection,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        Log::channel(config('logging.default'))->info('mongo_fallback', $payload);
    }

    public function retrieve(string $collection, array $filter): array
    {
        if (class_exists(\MongoDB\Client::class)) {
            try {
                $client = new \MongoDB\Client(config('mongodb.uri'));
                $cursor = $client
                    ->selectDatabase(config('mongodb.database'))
                    ->selectCollection(config("mongodb.collections.$collection", $collection))
                    ->find($filter);

                return iterator_to_array($cursor);
            } catch (\Throwable $exception) {
                Log::warning('MongoDB read failed.', [
                    'collection' => $collection,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return [];
    }
}
