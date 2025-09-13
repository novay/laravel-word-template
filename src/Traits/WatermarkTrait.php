<?php

namespace Novay\Word\Traits;

use Illuminate\Support\Facades\Config;

trait WatermarkTrait
{
    public function addWatermark(array $options = []): self
    {
        $default = Config::get('word.watermark.text', []);
        $style = array_merge($default, $options);

        if (!empty($style['text'])) {
            $this->phpWord->addWatermarkText($style['text'], $style);
        }

        if (!empty($style['image']) && file_exists($style['image']['path'] ?? '')) {
            foreach ($this->phpWord->getSections() as $section) {
                $section->addWatermarkImage($style['image']['path'], $style['image']);
            }
        }

        return $this;
    }
}
