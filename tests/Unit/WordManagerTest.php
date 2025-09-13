<?php

use Novay\Word\Facades\Word;

it('returns BuilderService from Word::builder', function () {
    $builder = Word::builder();
    expect($builder)->toBeInstanceOf(\Novay\Word\Services\BuilderService::class);
});

it('returns TemplateService from Word::template', function () {
    $template = Word::template(resource_path('word-templates/invoice.docx'));
    expect($template)->toBeInstanceOf(\Novay\Word\Services\TemplateService::class);
});
