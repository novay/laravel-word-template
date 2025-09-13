<?php

namespace Novay\Word\Contracts;

interface Exportable
{
    /**
     * Save the document to a specific path.
     *
     * @param string $path
     * @return string Full path of saved file
     */
    public function save(string $path): string;

    /**
     * Download the document via browser.
     *
     * @param string|null $filename
     * @return \Illuminate\Http\Response
     */
    public function download(?string $filename = null);

    /**
     * Export the document to a specific format.
     *
     * @param string $format
     * @param string|null $path
     * @return string|null Path of exported file if saved
     */
    public function export(string $format, ?string $path = null): ?string;
}
