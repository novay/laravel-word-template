<?php

namespace Novay\Word\Services;

use Novay\Word\Contracts\Exportable;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use Illuminate\Support\Facades\Config;
use PhpOffice\PhpWord\Style\ListItem;

class BuilderService implements Exportable
{
    protected PhpWord $phpWord;
    protected $section;
    protected array $styles = [];
    protected array $elements = [];

    public function __construct()
    {
        $this->phpWord = new PhpWord();
        $this->section = $this->phpWord->addSection();
        $this->styles = Config::get('word.builder', []);
    }

    /**
     * Add title / heading
     */
    public function addTitle(string $text, int $level = 1): self
    {
        $headingStyles = $this->styles['heading_styles'] ?? [];
        $style = $headingStyles[$level] ?? ['size' => 16, 'bold' => true];

        $this->section->addText(
            $text,
            ['bold' => $style['bold'] ?? true, 'size' => $style['size'] ?? 16],
            ['spaceAfter' => 120]
        );

        $this->elements[] = ['type' => 'title', 'text' => $text, 'level' => $level];

        return $this;
    }

    /**
     * Add paragraph text
     */
    public function addText(string $text, array $fontStyle = [], array $paragraphStyle = []): self
    {
        $defaultFont = Config::get('word.default_font', []);
        $defaultParagraph = $this->styles['paragraph'] ?? [];

        $font = array_merge($defaultFont, $fontStyle);
        $para = array_merge($defaultParagraph, $paragraphStyle);

        $this->section->addText($text, $font, $para);
        $this->elements[] = ['type' => 'text', 'text' => $text];

        return $this;
    }

    /**
     * Tambahkan list item
     *
     * @param array|string $items Bisa string atau array
     * @param int $depth Tingkat indentasi list
     * @param int $type List type: bullet / number / multilevel
     */
    public function addList($items, int $depth = 0, int $type = ListItem::TYPE_BULLET_FILLED): self
    {
        if (is_string($items)) {
            $this->section->addListItem($items, $depth, null, $type);
        } elseif (is_array($items)) {
            foreach ($items as $item) {
                if (is_array($item) && isset($item['text'], $item['children'])) {
                    // Nested list
                    $this->section->addListItem($item['text'], $depth, null, $type);
                    $this->addList($item['children'], $depth + 1, $type);
                } else {
                    $this->section->addListItem($item, $depth, null, $type);
                }
            }
        }

        $this->elements[] = ['type' => 'list', 'items' => $items, 'depth' => $depth, 'listType' => $type];

        return $this;
    }

    /**
     * Page Break / New Page
     */
    public function addPageBreak(): self
    {
        $this->section->addPageBreak();
        $this->elements[] = ['type' => 'pagebreak'];
        return $this;
    }

    /**
     * Add image
     */
    public function addImage(string $path, array $style = []): self
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("Image not found: $path");
        }

        $defaultStyle = Config::get('word.image', []);
        $finalStyle = array_merge($defaultStyle, $style);

        $this->section->addImage($path, $finalStyle);
        $this->elements[] = ['type' => 'image', 'path' => $path];

        return $this;
    }

    /**
     * Add table
     */
    public function addTable(array $rows, array $style = []): self
    {
        $table = $this->section->addTable($style);
        foreach ($rows as $row) {
            $table->addRow();
            foreach ($row as $cell) {
                $table->addCell()->addText($cell);
            }
        }

        $this->elements[] = ['type' => 'table', 'rows' => $rows];
        return $this;
    }

    /**
     * Add chart
     */
    public function addChart(string $type, array $data, array $style = []): self
    {
        $chartDefaults = Config::get('word.chart', []);
        $finalStyle = array_merge($chartDefaults, $style);

        $this->section->addChart($type, $data, $finalStyle);
        $this->elements[] = ['type' => 'chart', 'data' => $data];

        return $this;
    }

    /**
     * Add watermark
     */
    public function addWatermarkText(string $text, array $style = []): self
    {
        $watermark = Config::get('word.watermark.text', []);
        $finalStyle = array_merge($watermark, $style);

        $this->phpWord->addWatermarkText($text, $finalStyle);
        $this->elements[] = ['type' => 'watermark', 'text' => $text];

        return $this;
    }

    /**
     * Add signature
     */
    public function addSignature(string $path, array $style = []): self
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("Signature image not found: $path");
        }

        $signature = Config::get('word.signature', []);
        $finalStyle = array_merge($signature, $style);

        $this->section->addImage($path, $finalStyle);
        $this->elements[] = ['type' => 'signature', 'path' => $path];

        return $this;
    }

    /**
     * Save document
     */
    public function save(string $path): string
    {
        if ($callback = Config::get('word.events.before_save')) {
            call_user_func($callback, $this);
        }

        $writer = IOFactory::createWriter($this->phpWord, 'Word2007');
        $writer->save($path);

        if ($callback = Config::get('word.events.after_save')) {
            call_user_func($callback, $path, $this);
        }

        return $path;
    }

    /**
     * Download via browser
     */
    public function download(?string $filename = null)
    {
        $filename = $filename ?? $this->generateFilename();

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
     * Export multi-format
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

    /**
     * Generate filename based on config
     */
    protected function generateFilename(): string
    {
        $prefix = Config::get('word.filename.prefix', 'document_');
        $suffix = Config::get('word.filename.suffix', '');
        $timestamp = Config::get('word.filename.timestamp', true);
        $ext = Config::get('word.filename.extension', 'docx');

        $name = $prefix . $suffix;
        if ($timestamp) {
            $name .= '_' . date('Ymd_His');
        }
        return $name . '.' . $ext;
    }
}
