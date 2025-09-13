<?php

namespace Novay\Word\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Novay\Word\Services\BuilderService builder()
 * @method static \Novay\Word\Services\TemplateService template(string $path)
 * @method static \Novay\Word\Services\CollectionService collection(?string $templatePath = null)
 * @method static \Novay\Word\Services\AdvancedService merge(array $files, array $options = [])
 *
 * @see \Novay\Word\WordManager
 */
class Word extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'word';
    }
}
