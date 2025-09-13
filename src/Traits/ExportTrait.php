<?php

namespace Novay\Word\Traits;

use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Config;

trait ExportTrait
{
    protected function getPhpWord(): \PhpOffice\PhpWord\PhpWord
    {
        // untuk TemplateService
        if (isset($this->template) && method_exists($this->template, 'getTemplateDocument')) {
            return $this->template->getTemplateDocument(); 
        }

        // untuk BuilderService / AdvancedService
        if (isset($this->phpWord)) {
            return $this->phpWord; 
        }

        throw new \RuntimeException("Cannot get PhpWord instance.");
    }

    /**
     * Save document ke path tertentu
     */
    public function save(string $path): string
    {
        if ($callback = Config::get('word.events.before_save')) {
            call_user_func($callback, $this);
        }
        
        if (isset($this->template)) {
            $writer = $this->template;
        } else {
            $writer = IOFactory::createWriter($this->getPhpWord(), 'Word2007');
        }

        $writer->save($path);

        if ($callback = Config::get('word.events.after_save')) {
            call_user_func($callback, $path, $this);
        }

        return $path;
    }

    /**
     * Download document
     */
    public function download(?string $filename = null)
    {
        $filename = $filename ?? 'document_' . date('Ymd_His') . '.docx';
        $path = $this->save(tempnam(sys_get_temp_dir(), 'word_') . '.docx');

        if ($callback = Config::get('word.events.before_download')) {
            call_user_func($callback, $path, $this);
        }

        $response = response()->download(
            $path,
            $filename,
            Config::get('word.download.headers', [])
        )->deleteFileAfterSend(Config::get('word.download.delete_after_send', true));

        if ($callback = Config::get('word.events.after_download')) {
            call_user_func($callback, $path, $response, $this);
        }

        return $response;
    }

    /**
     * Export document ke format lain
     */
    public function export(string $format, ?string $path = null): ?string
    {
        $format = strtolower($format);

        if (!array_key_exists($format, Config::get('word.formats'))) {
            throw new \InvalidArgumentException("Format $format not supported.");
        }

        $path = $path ?? tempnam(sys_get_temp_dir(), 'word_') . '.' . $format;
        $writerType = Config::get("word.formats.$format", 'Word2007');

        $writer = IOFactory::createWriter($this->phpWord, $writerType);
        $writer->save($path);

        return $path;
    }

    

    
}
