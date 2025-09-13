<?php

use Novay\Word\Facades\Word;

it('can replace variables in template', function () {
    $templatePath = resource_path('word-templates/invoice.docx');
    $filePath = storage_path('app/test-invoice.docx');

    Word::template($templatePath)
        ->replace([
            'customer' => 'Budi',
            'amount' => 'Rp 1.500.000',
        ])
        ->save($filePath);

    $this->assertFileExists($filePath);
    unlink($filePath);
});
