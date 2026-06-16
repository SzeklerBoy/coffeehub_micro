<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MenuServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.microservices.menu');
    }

    /**
     * Fetch menu items as flat locale-aware arrays.
     * Pass $ids to restrict to specific items; omit to fetch all.
     */
    public function getItems(array $ids = [], string $locale = 'en'): array
    {
        $query = ['locale' => $locale];

        if ($ids) {
            $query['ids'] = implode(',', $ids);
        }

        $response = Http::timeout(5)->acceptJson()->get($this->baseUrl.'/menu', $query);

        return $response->successful() ? $response->json() : [];
    }

    public function getCategories(string $locale = 'en'): array
    {
        $response = Http::timeout(3)->acceptJson()->get($this->baseUrl.'/categories', ['locale' => $locale]);

        return $response->successful() ? $response->json() : [];
    }
}
