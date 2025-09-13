<?php

namespace Novay\Word\Traits;

use Illuminate\Support\Facades\Config;

trait TemplateValueTrait
{
    /**
     * Mengganti satu variabel di dalam template.
     *
     * @param string $key Nama variabel yang akan diganti.
     * @param string $value Nilai baru untuk variabel tersebut.
     * @return $this
     */
    public function setValue(string $key, string $value): self
    {
        return $this->setValues([$key => $value]);
    }

    /**
     * Mengganti beberapa variabel di dalam template.
     *
     * @param array $variables Daftar key-value variabel yang akan diganti.
     * @return $this
     */
    public function setValues(array $variables): self
    {
        foreach ($variables as $key => $value) {
            // Lewati jika nilai adalah array (gunakan setLoop).
            if (is_array($value)) continue;

            $placeholder = $this->wrapKey($key);

            $this->template->setValue($placeholder, $value);
        }

        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }
}