<?php

namespace Novay\Word\Services;

class CollectionService
{
    protected ?string $templatePath;
    protected array $items = [];

    public function __construct(?string $templatePath = null)
    {
        $this->templatePath = $templatePath;
    }

    public function replaceEach(array $collection, \Closure $callback = null): self
    {
        $this->items = $collection;
        $this->callback = $callback;
        return $this;
    }

    public function buildEach(array $collection, \Closure $callback): self
    {
        $this->items = $collection;
        $this->builderCallback = $callback;
        return $this;
    }

    public function save(string $path): array
    {
        $saved = [];
        foreach ($this->items as $key => $item) {
            $filename = $this->callback ? call_user_func($this->callback, $item) : $key . '.docx';
            $filePath = rtrim($path, '/') . '/' . $filename;

            if ($this->templatePath) {
                $template = new TemplateService($this->templatePath);
                $template->replace($item)->save($filePath);
            } elseif (isset($this->builderCallback)) {
                $builder = new BuilderService();
                ($this->builderCallback)($builder, $item);
                $builder->save($filePath);
            }

            $saved[] = $filePath;
        }
        return $saved;
    }

    public function downloadAsZip(string $zipName)
    {
        $files = $this->save(sys_get_temp_dir());
        $zip = new \ZipArchive();
        $zipPath = sys_get_temp_dir() . '/' . $zipName;
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
