<?php

namespace Novay\Word\Services;

use PhpOffice\PhpWord\TemplateProcessor;

class WordGenerator
{
    protected TemplateProcessor $template;

    public function load(string $templatePath): self
    {
        $this->template = new TemplateProcessor($templatePath);
        return $this;
    }

    public function setValue(string $key, $value): self
    {
        $this->template->setValue($key, $value);
        return $this;
    }

    public function setValues(array $data): self
    {
        foreach ($data as $key => $value) {
            $this->setValue($key, $value);
        }
        return $this;
    }

    public function setLoop(string $key, array $rows): self
    {
        $this->template->cloneRow($key, count($rows));
        foreach ($rows as $i => $row) {
            foreach ($row as $colKey => $colValue) {
                $this->template->setValue("{$colKey}#".($i+1), $colValue);
            }
        }
        return $this;
    }

    public function setImage(string $key, string $path, int $width = 200, int $height = 200): self
    {
        $default = config('word.image');

        $this->template->setImageValue($key, [
            'path'   => $path,
            'width'  => $width ?? $default['width'],
            'height' => $height ?? $default['height'],
            'ratio'  => $default['ratio'],
        ]);

        return $this;
    }

    public function saveAs(string $outputPath): string
    {
        $this->template->saveAs($outputPath);
        return $outputPath;
    }
}
