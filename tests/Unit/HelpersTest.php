<?php

it('returns correct storage path', function () {
    $path = word_storage('file.docx');
    expect($path)->toContain('storage/app/word/file.docx');
});

it('returns correct template path', function () {
    $path = word_template('invoice.docx');
    expect($path)->toContain('resource/word-templates/invoice.docx');
});

it('validates format correctly', function () {
    expect(word_is_valid_format('docx'))->toBeTrue();
    expect(word_is_valid_format('exe'))->toBeFalse();
});
