<?php

use Novay\Word\Facades\Word;

it('can create a document with builder', function () {
    $filePath = storage_path('app/test-builder.docx');

    Word::builder()
        ->addTitle('Judul Dokumen', 1)
        ->addText('Isi teks dokumen')
        ->save($filePath);

    $this->assertFileExists($filePath);
    unlink($filePath);
});

it('can add page break', function () {
    $filePath = storage_path('app/test-pagebreak.docx');

    Word::builder()
        ->addText('Page 1')
        ->addPageBreak()
        ->addText('Page 2')
        ->save($filePath);

    $this->assertFileExists($filePath);
    unlink($filePath);
});
