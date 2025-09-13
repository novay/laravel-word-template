<?php

namespace Novay\Word\Services;

use Novay\Word\Contracts\Exportable;
use Novay\Word\Traits\MergeTrait;
use Novay\Word\Traits\WatermarkTrait;
use Novay\Word\Traits\SignatureTrait;
use Novay\Word\Traits\ExportTrait;
use PhpOffice\PhpWord\PhpWord;

class AdvancedService implements Exportable
{
    use MergeTrait, WatermarkTrait, SignatureTrait, ExportTrait;

    protected PhpWord $phpWord;

    public function __construct(array $files = [])
    {
        $this->phpWord = new PhpWord();
        if (!empty($files)) {
            $this->merge($files);
        }
    }

    public function getPhpWord(): PhpWord
    {
        return $this->phpWord;
    }
}
