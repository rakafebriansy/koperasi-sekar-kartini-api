<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class FileController extends Controller
{
    public function download($path)
    {
        $filePath = storage_path('app/public/' . $path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->streamDownload(function () use ($filePath) {
            readfile($filePath);
        }, basename($filePath), [
            'Content-Type' => mime_content_type($filePath),
            'Content-Length' => filesize($filePath),
            'Cache-Control' => 'no-cache',
        ]);
    }


    public function show($path)
    {
        $fullPath = storage_path("app/public/$path");

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $mime = mime_content_type($fullPath);

        return Response::file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => "inline; filename=\"" . basename($fullPath) . "\""
        ]);
    }

}
