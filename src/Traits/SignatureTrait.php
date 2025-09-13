<?php

namespace Novay\Word\Traits;

use Illuminate\Support\Facades\Config;

trait SignatureTrait
{
    public function addSignature(array $options = []): self
    {
        $signature = array_merge(Config::get('word.signature', []), $options);

        if (!empty($signature['image_path']) && file_exists($signature['image_path'])) {
            foreach ($this->phpWord->getSections() as $section) {
                $section->addImage($signature['image_path'], $signature);
            }
        }

        return $this;
    }
}
