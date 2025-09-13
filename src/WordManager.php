<?php

namespace Novay\Word;

use Novay\Word\Services\BuilderService;
use Novay\Word\Services\TemplateService;
use Novay\Word\Services\CollectionService;
use Novay\Word\Services\AdvancedService;

class WordManager
{
    public function builder(): BuilderService
    {
        return new BuilderService();
    }

    public function template(string $templatePath): TemplateService
    {
        return new TemplateService($templatePath);
    }

    public function load(string $templatePath): TemplateService
    {
        return $this->template($templatePath);
    }

    public function collection(?string $templatePath = null): CollectionService
    {
        return new CollectionService($templatePath);
    }

    public function merge(array $files, array $options = []): AdvancedService
    {
        $service = new AdvancedService($files);
        if (!empty($files)) {
            $service->merge($files, $options);
        }
        return $service;
    }
}
