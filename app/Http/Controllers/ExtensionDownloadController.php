<?php

namespace App\Http\Controllers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class ExtensionDownloadController extends Controller
{
    /**
     * Dynamically packages and serves the extension as firstbid-extension.zip
     * Route: GET /extension/download (Protected - Requires Auth & Active Plan)
     */
    public function download()
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to download and install the FirstBid.in extension.');
        }

        if (! $user->is_approved) {
            return redirect()->route('pending')->with('error', 'Your account is pending approval before you can access the extension.');
        }

        if (! $user->canGenerate()) {
            return redirect()->route('dashboard')->with('error', 'Please upgrade your plan to download and use the browser extension.');
        }

        $extensionDir = base_path('extension');
        $zipPath = storage_path('app/firstbid-extension.zip');

        if (! file_exists($extensionDir)) {
            abort(404, 'Extension source folder not found.');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create extension ZIP archive.');
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($extensionDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (! $file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($extensionDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return response()->download($zipPath, 'firstbid-extension.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }
}
