<?php

namespace Novay\Word\Services;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Config;

class ExportService
{
    /**
     * Export PhpWord object to specific format.
     *
     * @param PhpWord $phpWord
     * @param string $format Supported: docx, pdf, html, rtf, odt, txt
     * @param string|null $path
     * @return string Path of exported file
     */
    public static function export(PhpWord $phpWord, string $format, ?string $path = null): string
    {
        $format = strtolower($format);
        $supportedFormats = Config::get('word.formats', []);

        if (!isset($supportedFormats[$format])) {
            throw new \InvalidArgumentException("Export format '$format' is not supported.");
        }

        $path = $path ?? tempnam(sys_get_temp_dir(), 'word_') . '.' . $format;
        $writerType = $supportedFormats[$format];

        $writer = IOFactory::createWriter($phpWord, $writerType);
        $writer->save($path);

        return $path;
    }

    /**
     * Export TemplateService object
     *
     * @param \Novay\Word\Services\TemplateService $template
     * @param string $format
     * @param string|null $path
     * @return string
     */
    public static function exportTemplate(TemplateService $template, string $format, ?string $path = null): string
    {
        $phpWord = $template->getTemplateDocument();
        return self::export($phpWord, $format, $path);
    }

    /**
     * Export BuilderService object
     *
     * @param \Novay\Word\Services\BuilderService $builder
     * @param string $format
     * @param string|null $path
     * @return string
     */
    public static function exportBuilder(BuilderService $builder, string $format, ?string $path = null): string
    {
        $phpWord = $builder->getPhpWord();
        return self::export($phpWord, $format, $path);
    }

    /**
     * Export AdvancedService object
     *
     * @param \Novay\Word\Services\AdvancedService $advanced
     * @param string $format
     * @param string|null $path
     * @return string
     */
    public static function exportAdvanced(AdvancedService $advanced, string $format, ?string $path = null): string
    {
        $phpWord = $advanced->getPhpWord();
        return self::export($phpWord, $format, $path);
    }
}
