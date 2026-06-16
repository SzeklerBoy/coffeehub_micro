<?php

namespace App\Services;

use App\Exceptions\BasicWrapperException;
use App\Models\Language;
use App\Models\Translation;

class XliffImporter
{
    public function insertToDatabase(array $dbItems, string $targetLang): void
    {
        $language = Language::where('code', $targetLang)->first();

        if (! $language) {
            throw new BasicWrapperException("Language '$targetLang' not found.");
        }

        foreach ($dbItems as $item) {
            if (! isset($item['id'], $item['target'])) {
                continue;
            }

            $parts = explode('.', $item['id']);

            if (count($parts) !== 3) {
                continue;
            }

            [, $itemID, $field] = $parts;

            if (! in_array($field, ['name', 'description', 'category'])) {
                continue;
            }

            $targetText = $item['target'];

            // Fetch or create a translation record
            $translation = Translation::firstOrNew([
                'menu_item_id' => $itemID,
                'language_id' => $language->id,
            ]);

            // If this is a new record, ensure required fields are set to default/null
            if ($translation->exists) {
                if (! empty($targetText)) {
                    $translation->$field = $targetText;
                    $translation->save();
                }

            } else {
                $translation->name = $field === 'name' ? $targetText : '';
                $translation->description = $field === 'description' ? $targetText : null;
                $translation->category = $field === 'category' ? $targetText : '';
                $translation->save();
            }

            $translation->save();
        }
    }

    public function insertToJsonFile(array $dbItems, string $targetLang)
    {
        $jsonPath = resource_path("locales/{$targetLang}.json");

        // Load current JSON, or start with empty structure
        $json = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];

        foreach ($dbItems as $item) {
            if (! isset($item['id'], $item['target']) || empty($item['target'])) {
                continue; // skip if missing or empty translation
            }

            if (! str_starts_with($item['id'], 'json.')) {
                continue;
            }

            $keyPath = explode('.', substr($item['id'], 5));
            $value = $item['target'];

            $ref = &$json;
            foreach ($keyPath as $i => $key) {
                // If we’re at the last item in the path, set the value
                if ($i === count($keyPath) - 1) {
                    $ref[$key] = $value;
                } else {
                    if (! isset($ref[$key]) || ! is_array($ref[$key])) {
                        $ref[$key] = [];
                    }
                    $ref = &$ref[$key];
                }
            }
        }

        file_put_contents($jsonPath, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
