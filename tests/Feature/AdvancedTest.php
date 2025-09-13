<?php

use Novay\Word\Facades\Word;

it('can merge documents', function () {
    $file1 = storage_path('app/test1.docx');
    $file2 = storage_path('app/test2.docx');
    file_put_contents($file1, 'File 1');
    file_put_contents($file2, 'File 2');

    $mergedPath = storage_path('app/test-merged.docx');
    Word::merge([$file1, $file2])->save($mergedPath);

    $this->assertFileExists($mergedPath);

    unlink($file1);
    unlink($file2);
    unlink($mergedPath);
});

it('can add watermark', function () {
    $filePath = storage_path('app/test-watermark.docx');
    Word::builder()
        ->addText('Confidential')
        ->addWatermarkText('CONFIDENTIAL')
        ->save($filePath);

    $this->assertFileExists($filePath);
    unlink($filePath);
});
