<?php

namespace Novay\Word\Traits;

use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Config;

trait ExportableTrait
{
    /**
     * Download via browser
     */
    public function download(?string $filename = null)
    {
        $filename = $filename ?? $this->generateFilename();

        $path = $this->save($filename);

        // Event before_download
        if ($callback = Config::get('word.events.before_download')) {
            call_user_func($callback, $path, $this);
        }

        $response = response()->download(
            $path,
            $filename,
            Config::get('word.download.headers', [])
        )->deleteFileAfterSend(Config::get('word.download.delete_after_send', true));

        // Event after_download
        if ($callback = Config::get('word.events.after_download')) {
            call_user_func($callback, $path, $response, $this);
        }

        return $response;
    }

    /**
     * Save to disk.
     */
    public function save(string $filename): string
    {
        if ($callback = Config::get('word.events.before_save')) {
            call_user_func($callback, $this);
        }

        $storagePath = rtrim(Config::get('word.storage_path', storage_path('app/word')), '/');

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        $path = $storagePath . '/' . ltrim($filename, '/');

        $this->template->saveAs($path);

        if ($callback = Config::get('word.events.after_save')) {
            call_user_func($callback, $path, $this);
        }

        return $path;
    }

    /**
     * Export to specific file
     */
    public function export(string $format, ?string $path = null): ?string
    {
        return '';
    }
}