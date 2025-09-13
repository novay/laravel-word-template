<?php

namespace Novay\Word\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishTemplateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:publish-templates
                            {--force : Overwrite existing templates}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish default Word templates to resources/word-templates';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $source = __DIR__ . '/../../resources/templates';
        $destination = resource_path('word-templates');

        if (! File::exists($source)) {
            $this->error("Source template folder not found: $source");
            return self::FAILURE;
        }

        if (! File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
            $this->info("Created folder: $destination");
        }

        $files = File::files($source);

        foreach ($files as $file) {
            $destFile = $destination . '/' . $file->getFilename();

            if (File::exists($destFile) && ! $this->option('force')) {
                $this->warn("Skipping existing file: {$file->getFilename()}");
                continue;
            }

            File::copy($file->getPathname(), $destFile);
            $this->info("Published: {$file->getFilename()}");
        }

        $this->info('All templates published successfully.');
        return self::SUCCESS;
    }
}
