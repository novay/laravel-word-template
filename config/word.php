<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Storage Path
    |--------------------------------------------------------------------------
    |
    | Path default untuk menyimpan dokumen yang di-generate.
    | Bisa berupa path lokal atau cloud storage jika diubah.
    |
    */
    'storage_path' => storage_path('app/word'),

    /*
    |--------------------------------------------------------------------------
    | Template Path
    |--------------------------------------------------------------------------
    |
    | Folder default untuk menyimpan template Word (.docx) bawaan.
    |
    */
    'template_path' => resource_path('word-templates'),

    /*
    |--------------------------------------------------------------------------
    | Placeholder Format
    |--------------------------------------------------------------------------
    |
    | Format untuk variabel di template. Contoh: ${variable} atau %variable%
    |
    */
    'placeholder' => [
        'prefix' => '${',
        'suffix' => '}',
        'trim_whitespace' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Filename Options
    |--------------------------------------------------------------------------
    |
    | Default options untuk generate filename.
    |
    */
    'filename' => [
        'prefix' => 'document_',
        'suffix' => '',
        'timestamp' => true,   // append timestamp
        'extension' => 'docx', // default extension
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Formats
    |--------------------------------------------------------------------------
    |
    | Format dokumen yang didukung untuk export/multi-format.
    |
    */
    'formats' => [
        'docx' => 'Word2007',
        'pdf'  => 'PDF',
    ],

    'pdf_renderer' => 'DomPDF', // MPDF, TCPDF, DomPDF
    'pdf_renderer_path' => base_path('vendor/dompdf/dompdf'),

    /*
    |--------------------------------------------------------------------------
    | Default Font
    |--------------------------------------------------------------------------
    |
    | Font default untuk Builder Mode.
    |
    */
    'default_font' => [
        'name' => 'Arial',
        'size' => 12,
        'color' => '000000',
    ],

    /*
    |--------------------------------------------------------------------------
    | Watermark Defaults
    |--------------------------------------------------------------------------
    |
    | Konfigurasi default watermark teks / gambar.
    |
    */
    'watermark' => [
        'text' => [
            'font' => 'Arial',
            'size' => 48,
            'color' => 'CCCCCC',
            'rotation' => 45,
            'opacity' => 0.2,
        ],
        'image' => [
            'width' => 200,
            'height' => 200,
            'margin_top' => 0,
            'margin_left' => 0,
            'opacity' => 0.2,
        ],
    ],

    

    /*
    |--------------------------------------------------------------------------
    | Chart Defaults
    |--------------------------------------------------------------------------
    |
    | Default konfigurasi chart di Builder Mode.
    |
    */
    'chart' => [
        'width' => 600,
        'height' => 400,
        'font' => 'Arial',
        'font_size' => 12,
    ],

    /*
    |--------------------------------------------------------------------------
    | Signature Defaults
    |--------------------------------------------------------------------------
    |
    | Konfigurasi default untuk signature digital.
    |
    */
    'signature' => [
        'width' => 150,
        'height' => 50,
        'image_path' => null,
        'certificate_path' => null,
        'certificate_password' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Settings
    |--------------------------------------------------------------------------
    |
    | Default page size, margin, orientation untuk Builder Mode.
    |
    */
    'page' => [
        'size' => 'A4',
        'orientation' => 'portrait', // portrait / landscape
        'margin_top' => 1440,    // twip (1/20 point)
        'margin_right' => 1440,
        'margin_bottom' => 1440,
        'margin_left' => 1440,
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging & Debug
    |--------------------------------------------------------------------------
    |
    | Aktifkan debug untuk logging error atau informasi tambahan.
    |
    */
    'debug' => env('WORD_DEBUG', false),


    /*
    |--------------------------------------------------------------------------
    | Image Settings
    |--------------------------------------------------------------------------
    |
    | Konfigurasi default untuk insert image di Builder atau Template.
    |
    */
    'image' => [
        'width' => 300,          // default width dalam pixel
        'height' => 200,         // default height
        'alignment' => 'center', // left, center, right
    ],

    /*
    |--------------------------------------------------------------------------
    | Builder Style Defaults
    |--------------------------------------------------------------------------
    |
    | Style default yang digunakan saat Builder Mode (fonts, paragraph, headings)
    |
    */
    'builder' => [
        'default_font' => [
            'name' => 'Arial',
            'size' => 12,
            'color' => '000000',
        ],
        'paragraph' => [
            'alignment' => 'left',
            'spaceAfter' => 120,
        ],
        'heading_styles' => [
            1 => ['size' => 16, 'bold' => true],
            2 => ['size' => 14, 'bold' => true],
            3 => ['size' => 12, 'bold' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Download Options
    |--------------------------------------------------------------------------
    |
    | Opsi default ketika mendownload file via browser.
    |
    */
    'download' => [
        'headers' => [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        'delete_after_send' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Event hooks
    |--------------------------------------------------------------------------
    | Callback functions untuk hook sebelum / sesudah save dan download
    | Contoh: 'before_save' => function($service) { ... }
    */
    'events' => [
        'before_save'      => null,
        'after_save'       => null,
        'before_download'  => null,
        'after_download'   => null,
    ],

];
