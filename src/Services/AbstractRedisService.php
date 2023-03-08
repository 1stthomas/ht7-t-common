<?php

namespace Ht7FfhsBsct\Common\Services;

use Carbon\Carbon;
use Ht7FfhsBsct\Common\Enums\EventFetchTypes;
use Ht7FfhsBsct\Common\Events\Event;
use Illuminate\Support\Facades\Redis;

abstract class AbstractRedisService
{
    abstract public function getServiceName(): string;
    public function addProcessedEvent(array $event): void
    {
        Redis::rpush(
            $this->getServiceName() . '-' . EventFetchTypes::PROCESSED_EVENTS_KEY,
            $event['id'],
        );
    }
    public function getUnprocessedEvents(): array
    {
        $fromTimestamp = $this->getLastProcessedEventId();

        $allEvents = $this->getEventsAfter($fromTimestamp);

        return $this->parseEvents($allEvents);
    }
    public function publish(Event $event): void
    {
        Redis::xadd(EventFetchTypes::ALL_EVENTS_KEY, '*', [
            'event' => $event->toJson(),
            'service' => $this->getServiceName(),
            'createdAt' => now()->format('Y-m-d H:i:s')
        ]);
    }
    protected function getEventsAfter(string $start): array
    {
        $events = Redis::xRange(
                EventFetchTypes::ALL_EVENTS_KEY,
                $start,
                (int) Carbon::now()->valueOf()
        );

        if (!$events) {
            return [];
        }

        unset($events[$start]);

        return $events;
    }
    /**
     * @return array{type: string, data: array, id: string}
     */
    protected function parseEvents(array $eventsFromRedis): array
    {
        return collect($eventsFromRedis)
                ->map(function (array $item, string $id) {
                    return array_merge(
                    json_decode($item['event'], true),
                    ['id' => $id]
                    );
                })->all();
    }
    private function getLastProcessedEventId(): string
    {
        $lastId = Redis::lindex(
                $this->getServiceName() . '-' . EventFetchTypes::PROCESSED_EVENTS_KEY,
                -1,
        );

        return empty($lastId) ? (string) Carbon::now()->subYears(10)->valueOf() : $lastId;
    }
}
