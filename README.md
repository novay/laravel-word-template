# Laravel Word Template

[![Latest Stable Version](https://poser.pugx.org/novay/laravel-word-template/v/stable)](https://packagist.org/packages/novay/laravel-word-template)
[![Total Downloads](https://poser.pugx.org/novay/laravel-word-template/downloads)](https://packagist.org/packages/novay/laravel-word-template)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

- [About](#about)
- [Requirements](#requirements)
- [Installation Instructions](#installation-instructions)
- [Basic Usage](#usage)
- [License](#license)

### About

Laravel Package to perform word replacement on files using document templates (.rtf) that have been provided.

[ID] Package Laravel untuk melakukan penggantian kata pada file menggunakan template dokumen (.rtf) yang sudah disediakan.

### Requirements
* [Laravel 5.1, 5.2, 5.3, 5.4, or 5.5+](https://laravel.com/docs/installation)

### Installation Instructions
1. From your projects root folder in terminal run:

    ```bash
        composer require novay/laravel-word-template
    ```

2. Register the package

    * Laravel 5.5 and up
    Uses package auto discovery feature, no need to edit the `config/app.php` file.

    * Laravel 5.4 and below
    Register the package with laravel in `config/app.php` under `providers` with the following:

    ```php
        'providers' => [
        ...
            Novay\WordTemplate\WordTemplateServiceProvider::class,
        ];

        'alias' => [
        ...
           'WordTemplate' => Novay\WordTemplate\Facade::class, 
        ];
    ```

### Basic Usage
1. You need to prepare the template document that you want, for example you can download [This File](https://raw.githubusercontent.com/novay/laravel-word-template/master/surat_pernyataan.rtf). If it's downloading automatically, you can just press Ctrl+S to save it manually as .rtf file.

2. You can use this `facade` to play with this :

```
	WordTemplate('file.rtf', array(), 'nama_file.doc');
``` 

	This method has 3 parameters:

	- Location of your document template file `Ex. public_path('template/document.rtf')`
	- The initial words along with their replacement in the arrays form. [See example](https://github.com/novay/laravel-word-template/blob/master/sample.php)
	- Specifies the file name when the file is downloaded `Ex. file.doc`

3. (Example) Copy this file in your routes directly for the instance :

```php
    Route::get('/', function () {
		$file = public_path('surat_pernyataan.rtf');
		
		$array = array(
			'[NOMOR_SURAT]' => '015/BT/SK/V/2017',
			'[PERUSAHAAN]' => 'CV. Borneo Teknomedia',
			'[NAMA]' => 'Melani Malik',
			'[NIP]' => '6472065508XXXX',
			'[ALAMAT]' => 'Jl. Manunggal Gg. 8 Loa Bakung, Samarinda',
			'[PERMOHONAN]' => 'Permohonan pengurusan pembuatan NPWP',
			'[KOTA]' => 'Samarinda',
			'[DIRECTOR]' => 'Noviyanto Rahmadi',
			'[TANGGAL]' => date('d F Y'),
		);

		$nama_file = 'surat-keterangan-kerja.doc';
		
		return WordTemplate::export($file, $array, $nama_file);
	});
```

### License
Laravel Word Template is licensed under the MIT license. Enjoy!