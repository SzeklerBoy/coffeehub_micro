<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Services\XliffExporter;
use App\Services\XliffImporter;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TranslationController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Translations/Index', [
            'languages' => Language::with('translations')->get(),
        ]);
    }

    public function exportXLIFF(Request $request, XliffExporter $exporter): BinaryFileResponse
    {
        $request->validate([
            'sourceLanguage' => 'required|exists:languages,code',
            'targetLanguage' => 'required',
        ]);
        $language = Language::where('code', $request->sourceLanguage)->firstOrFail();

        $xliffString = $exporter->createXliffHeader($language, $request->input('targetLanguage'));
        $xliffString = $exporter->exportFromDatabase($language, $xliffString);
        $xliffString = $exporter->exportFromJson(
            resource_path('locales/'.$language->code.'.json'),
            $xliffString
        );

        $filename = "translations_{$language->code}.xliff";
        $path = storage_path("xliff/{$filename}");

        if (! file_exists(storage_path('xliff'))) {
            mkdir(storage_path('xliff'), 0777, true);
        }

        file_put_contents($path, $xliffString);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function import(Request $request, XliffImporter $importer): JsonResponse
    {
        if (! $request->hasFile('file')) {
            return response()->json([
                'message' => 'No file uploaded',
            ], 422);
        }

        $file = $request->file('file');

        $xmlContent = file_get_contents($file->getRealPath());

        try {
            $xml = simplexml_load_string($xmlContent);

            $json = json_encode($xml);
            $array = json_decode($json, true);

            $targetLang = (string) $array['file']['@attributes']['target-language'] ?? null;

            $units = $array['file']['body']['trans-unit'] ?? [];

            $dbUnits = array_filter($units, function ($unit) {
                return isset($unit['@attributes']['id']) && str_starts_with($unit['@attributes']['id'], 'db.');
            });

            $dbUnitsTranslated = array_map(function ($unit) {
                return [
                    'id' => $unit['@attributes']['id'] ?? null,
                    'target' => $unit['target'] ?? null,
                ];
            }, $dbUnits);

            $importer->insertToDatabase($dbUnitsTranslated, $targetLang);

            $jsonUnits = array_filter($units, function ($unit) {
                return isset($unit['@attributes']['id']) && str_starts_with($unit['@attributes']['id'], 'json.');
            });

            $jsonUnitsTranslated = array_map(function ($unit) {
                return [
                    'id' => $unit['@attributes']['id'] ?? null,
                    'target' => $unit['target'] ?? null,
                ];
            }, $jsonUnits);

            $importer->insertToJsonFile($jsonUnitsTranslated, $targetLang);

        } catch (Exception $ex) {
            Log::error('Error parsing XML file: '.$ex->getMessage());

            return response()->json([
                'message' => 'Error parsing XML file',
            ], 422);
        }

        return response()->json('Import was successful', 200);
    }
}
