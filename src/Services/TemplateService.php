<?php

namespace Novay\Word\Services;

use Novay\Word\Contracts\Exportable;
use Novay\Word\Traits\TemplateImageTrait;
use Novay\Word\Traits\TemplateLoopTrait;
use Novay\Word\Traits\TemplateValueTrait;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Novay\Word\Traits\ExportableTrait;

class TemplateService implements Exportable
{
    use TemplateImageTrait, TemplateValueTrait, TemplateLoopTrait, ExportableTrait;

    protected PhpWord $phpWord;
    protected TemplateProcessor $template;

    protected string $placeholderPrefix;
    protected string $placeholderSuffix;

    protected string $templatePath;
    protected array $variables = [];
    protected array $images = [];

    public function __construct(string $path)
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $tmpPath = tempnam(sys_get_temp_dir(), 'word_') . '.docx';
            $contents = file_get_contents($path);
    
            if ($contents === false) {
                throw new \InvalidArgumentException("Cannot download template from URL: $path");
            }
    
            file_put_contents($tmpPath, $contents);
            $path = $tmpPath;
        }

        if (!preg_match('/^\//', $path) && !file_exists($path)) {
            $storagePath = rtrim(Config::get('word.storage_path', storage_path('app/word')), '/');
            $path = $storagePath . '/' . ltrim($path, '/');
        }

        if (!file_exists($path)) {
            throw new \InvalidArgumentException("Template not found: $path");
        }

        $this->phpWord = new PhpWord();
        $this->template = new TemplateProcessor($path);

        $this->templatePath = $path;

        $this->placeholderPrefix = Config::get('word.placeholder.prefix', '${');
        $this->placeholderSuffix = Config::get('word.placeholder.suffix', '}');
    }

    /**
     * Membangun string placeholder berdasarkan kunci variabel.
     *
     * Fungsi ini akan menambahkan prefix dan suffix placeholder,
     * namun tidak akan menggandakan jika kunci sudah dalam format placeholder.
     *
     * @param string $key Kunci variabel (misalnya, 'nama').
     * @return string String placeholder yang sudah dibentuk (misalnya, '${nama}').
     */
    protected function wrapKey(string $key): string
    {
        $prefix = config('word.placeholder.prefix', '${');
        $suffix = config('word.placeholder.suffix', '}');

        // Cegah penggandaan placeholder seperti "${nama}" menjadi "${${nama}}".
        if (Str::startsWith($key, $prefix) && Str::endsWith($key, $suffix)) {
            return $key;
        }

        if (config('word.placeholder.trim_whitespace', false)) {
            $key = trim($key);
        }

        return $prefix . $key . $suffix;
    }

    /**
     * Generate filename based on config.
     */
    protected function generateFilename(): string
    {
        $prefix = Config::get('word.filename.prefix', 'document_');
        $suffix = Config::get('word.filename.suffix', '');
        $timestamp = Config::get('word.filename.timestamp', true);
        $ext = Config::get('word.filename.extension', 'docx');

        $name = $prefix . $suffix;
        if ($timestamp) {
            $name .= date('Ymd_His');
        }

        return $name . '.' . $ext;
    }
}
