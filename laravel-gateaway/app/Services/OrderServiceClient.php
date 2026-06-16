<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OrderServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.microservices.order');
    }

    public function list(array $query = []): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->get($this->baseUrl.'/orders', $query));
    }

    public function all(): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->get($this->baseUrl.'/orders/all'));
    }

    public function find(string $orderUuid): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->get($this->baseUrl.'/orders/'.$orderUuid));
    }

    public function create(array $payload): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->post($this->baseUrl.'/orders', $payload));
    }

    public function updateStatus(string $orderUuid, string $status): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->put($this->baseUrl.'/orders/'.$orderUuid.'/status', [
            'status' => $status,
        ]));
    }

    public function delete(string $orderUuid): bool
    {
        return Http::timeout(5)->acceptJson()->delete($this->baseUrl.'/orders/'.$orderUuid)->successful();
    }

    public function removeItem(string $orderUuid, int $menuItemId): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->delete($this->baseUrl.'/orders/'.$orderUuid.'/items/'.$menuItemId));
    }

    public function markItemPaid(string $orderUuid, int $menuItemId, int $quantity): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->patch($this->baseUrl.'/orders/'.$orderUuid.'/items/'.$menuItemId.'/paid', [
            'quantity' => $quantity,
        ]));
    }

    public function eta(string $orderUuid, int $waiterCount = 1): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->get($this->baseUrl.'/orders/'.$orderUuid.'/eta', [
            'waiters' => $waiterCount,
        ]));
    }

    public function mostSoldItems(): ?array
    {
        return $this->json(Http::timeout(5)->acceptJson()->get($this->baseUrl.'/reports/most-sold'));
    }

    private function json(Response $response): ?array
    {
        if (! $response->successful()) {
            return null;
        }

        return $response->json();
    }
}