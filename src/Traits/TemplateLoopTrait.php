<?php

namespace Novay\Word\Traits;

trait TemplateLoopTrait
{
    /**
     * Support cloning rows for looping.
     * $key = base placeholder name in template (without #1)
     * $rows = array of associative arrays, supports nested arrays using dot notation keys
     */
    public function setLoop(string $key, array $rows): self
    {
        if (! $this->template) {
            throw new \RuntimeException('Template not loaded. Call template() first.');
        }

        $count = count($rows);
        if ($count === 0) {
            // if no rows, try to remove remaining placeholders by setting empty
            $this->template->cloneRow($key, 0);
            return $this;
        }

        $this->template->cloneRow($key, $count);

        foreach ($rows as $i => $row) {
            $index = $i + 1;

            // row can be nested arrays (e.g. ['user' => ['name' => 'A']])
            $flatten = $this->flattenArray($row);

            foreach ($flatten as $colKey => $colValue) {
                // The template expects keys like colKey#1
                $placeholder = "{$colKey}#{$index}";
                $this->template->setValue($this->wrapKey($placeholder), $colValue);
            }
        }

        return $this;
    }

    

    /**
     * Flatten nested arrays into dot notation keys.
     * e.g. ['user' => ['name' => 'Budi']] => ['user.name' => 'Budi']
     * But for template placeholders we replace dot with underscore (user_name)
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $val) {
            $newKey = $prefix === '' ? $key : $prefix . '.' . $key;
            if (is_array($val)) {
                $result = array_merge($result, $this->flattenArray($val, $newKey));
            } else {
                // convert dots to underscore for placeholder compatibility
                $result[str_replace('.', '_', $newKey)] = $val;
            }
        }
        return $result;
    }
}