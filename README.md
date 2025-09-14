# 📄 Laravel Word Template

[![Latest Version](https://img.shields.io/packagist/v/novay/laravel-word-template.svg?style=flat-square)](https://packagist.org/packages/novay/laravel-word-template)
[![Total Downloads](https://img.shields.io/packagist/dt/novay/laravel-word-template.svg?style=flat-square)](https://packagist.org/packages/novay/laravel-word-template)
[![License](https://img.shields.io/github/license/novay/laravel-word-template.svg?style=flat-square)](LICENSE.md)

**Laravel Word Template** is a Laravel package built on top of **[PHPWord](https://github.com/PHPOffice/PHPWord)** for creating, manipulating, and exporting Word/Office documents. It offers full support for **template replacement, looping, images, builder mode**, watermarks, merging, and even digital signatures.

![Example](https://raw.githubusercontent.com/novay/laravel-word-template/refs/heads/master/examples/laravel-wordtemplate.png)

### 🚀 Installation

```bash
composer require novay/laravel-word-template
```

Publish the configuration:
```bash
php artisan vendor:publish --provider="Novay\Word\Providers\WordServiceProvider"
```

### ⚡ Basic Usage

#### 1️⃣ Replace Value
```php
return Word::template(storage_path('app/templates/replace-values.docx'))
    ->replaceValue('nama', 'Novianto Rahmadi')
    ->replaceValue('app', 'Laravel WordTemplate')
    ->download('output.docx');
```

#### 2️⃣ Replace Images
```php
return Word::template(storage_path('app/templates/template.docx'))
    ->replaceImage('logo', public_path('logo.png'), [
        'width' => 120,
        'height'=> 120,
        'ratio' => true
    ])
    ->download('output.docx');
```

### 📚 Full Documentation

For more comprehensive documentation, please visit:
👉 [https://word.btekno.id](https://word.btekno.id)