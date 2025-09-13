<?php

namespace Novay\Word\Traits;

use Illuminate\Support\Facades\Config;

trait TemplateImageTrait
{
    /**
     * Replace single image.
     *
     * @param string|array $placeholder single key atau array key=>path/options
     * @param string|null $path path image jika placeholder string
     * @param array $options optional: width, height, ratio
     */
    public function setImage(string $key, string $path, array $options = []): self
    {
        return $this->setImages([
            $key => array_merge(['path' => $path], $options)
        ]);
    }


    /**
     * Replace multiple images.
     *
     * @param array $images Opsi tambahan: width, height, ratio
     * @return self
     */
    public function setImages(array $images): self
    {
        foreach ($images as $key => $img) {
            $path = $img['path'] ?? null;
            $width = $img['width'] ?? null;
            $height = $img['height'] ?? null;
            $ratio = $img['ratio'] ?? true;

            if (!$path || !file_exists($path)) {
                continue; 
            }

            $this->template->setImageValue($key, array_filter([
                'path' => $path,
                'width' => $width,
                'height' => $height,
                'ratio' => $ratio,
            ]));

            $this->images[$key] = array_filter([
                'path' => $path,
                'width' => $width,
                'height' => $height,
                'ratio' => $ratio,
            ]);
        }

        return $this;
    }
}