<?php

namespace Novay\Word\Support;

use Novay\Word\Services\WordGenerator;

if (! function_exists('word')) {
    function word(): WordGenerator 
    {
        return app('word');
    }
}

if (! function_exists('word_storage')) {
    function word_storage($path = '') {
        return storage_path('app/word/' . $path);
    }
}

if (! function_exists('word_public')) {
    function word_public($path = '') {
        return public_path('word/' . $path);
    }
}

if (! function_exists('word_formats')) {
    function word_formats(): array {
        return ['docx','pdf','html','odt','rtf','txt'];
    }
}

if (! function_exists('word_is_valid_format')) {
    function word_is_valid_format(string $format): bool {
        return in_array(strtolower($format), word_formats());
    }
}

if (! function_exists('word_template')) {
    function word_template(string $name): string {
        return resource_path('word-templates/' . $name);
    }
}

if (! function_exists('word_template_exists')) {
    function word_template_exists(string $name): bool {
        return file_exists(word_template($name));
    }
}

if (! function_exists('word_tempfile')) {
    function word_tempfile(string $suffix = '.docx'): string {
        return tempnam(sys_get_temp_dir(), 'word_') . $suffix;
    }
}

if (! function_exists('word_download_response')) {
    function word_download_response(string $path, ?string $filename = null) {
        $filename = $filename ?: basename($path);
        return response()->download($path, $filename);
    }
}
