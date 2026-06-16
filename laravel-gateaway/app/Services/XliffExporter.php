<?php

namespace App\Services;

use App\Exceptions\BasicWrapperException;
use App\Models\Language;
use App\Models\Translation;
use DOMDocument;
use Exception;
use SimpleXMLElement;

class XliffExporter
{
    public function createXliffHeader(Language $language, string $targetLang): string
    {
        $xliff = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xliff version="1.2" xmlns="urn:oasis:names:tc:xliff:document:1.2"></xliff>');
        $file = $xliff->addChild('file');
        $file->addAttribute('source-language', $language->code);
        $file->addAttribute('target-language', $targetLang);
        $file->addAttribute('datatype', 'plaintext');
        $file->addAttribute('original', 'xliff-core-1.2');

        return $xliff->asXML();
    }

    /**
     * @throws Exception
     */
    public function exportFromDatabase(Language $language, string $xmlString): string
    {
        $translations = Translation::where('language_id', $language->id)->get();

        $xliff = new SimpleXMLElement($xmlString);

        $file = $xliff->file;
        $body = $file->addChild('body');

        foreach ($translations as $translation) {
            $transUnit = $body->addChild('trans-unit');
            $transUnit->addAttribute('id', 'db.'.$translation->menuItem->id.'.name');
            $transUnit->addChild('source', $translation->name);
            $transUnit->addChild('target', '');
            $transUnit = $body->addChild('trans-unit');
            $transUnit->addAttribute('id', 'db.'.$translation->menuItem->id.'.description');
            $transUnit->addChild('source', $translation->description);
            $transUnit->addChild('target', '');
            $transUnit = $body->addChild('trans-unit');
            $transUnit->addAttribute('id', 'db.'.$translation->menuItem->id.'.category');
            $transUnit->addChild('source', $translation->category);
            $transUnit->addChild('target', '');
        }

        return $xliff->asXML();
    }

    public function exportFromJson(string $jsonPath, string $xmlString): string
    {
        if (! file_exists($jsonPath)) {
            throw new BasicWrapperException("File not found: $jsonPath");
        }

        $json = json_decode(file_get_contents($jsonPath), true);

        if (! is_array($json)) {
            throw new BasicWrapperException("Invalid JSON format in file: $jsonPath");
        }

        $flatJson = $this->flattenJson($json);

        $xliff = new SimpleXMLElement($xmlString);

        $file = $xliff->file;
        $body = isset($file->body) ? $file->body : $file->addChild('body');

        foreach ($flatJson as $key => $value) {
            $transUnit = $body->addChild('trans-unit');
            $transUnit->addAttribute('id', 'json.'.ltrim($key, '.'));
            $transUnit->addChild('source', htmlspecialchars((string) $value));
            $transUnit->addChild('target', '');
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xliff->asXML());

        return $dom->saveXML();
    }

    public function flattenJson(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $fullKey = $prefix === '' ? $key : rtrim($prefix, '.').'.'.$key;

            if (is_array($value)) {
                $result += $this->flattenJson($value, $fullKey);
            } else {
                $result[$fullKey] = $value;
            }
        }

        return $result;
    }
}
