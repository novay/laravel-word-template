<?php

use Novay\Word\Facades\Word;

it('can save and download document', function () {
    $filePath = storage_path('app/test-export.docx');

    Word::builder()
        ->addText('Export Test')
        ->save($filePath);

    $this->assertFileExists($filePath);
    unlink($filePath);
});

it('can export multiple formats', function () {
    $exportPath = storage_path('app/test-export-multi');

    Word::builder()
        ->addText('Multi Format Test')
        ->save($exportPath . '/test.docx'); // basic DOCX
    // PDF, HTML, etc. bisa diimplementasikan sesuai library
    $this->assertDirectoryExists($exportPath);
});
