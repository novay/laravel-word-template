# 📄 Laravel Word Template

[![Latest Version](https://img.shields.io/packagist/v/novay/laravel-word-template.svg?style=flat-square)](https://packagist.org/packages/novay/laravel-word-template)
[![Total Downloads](https://img.shields.io/packagist/dt/novay/laravel-word-template.svg?style=flat-square)](https://packagist.org/packages/novay/laravel-word-template)
[![License](https://img.shields.io/github/license/novay/laravel-word-template.svg?style=flat-square)](LICENSE.md)

Laravel Word Template adalah package Laravel berbasis [PHPWord](https://github.com/PHPOffice/PHPWord) untuk membuat, memanipulasi, dan mengekspor dokumen Word/Office. Dukungan lengkap untuk **template replace, looping, gambar, builder mode, multi-format export (DOCX, PDF, ODT, HTML)**, watermark, merge, hingga tanda tangan digital. 

---

## ✨ Fitur Utama
- 📄 **Template Replace** – isi template `.docx` dengan data, looping, dan gambar  
- ✍️ **Builder Mode** – buat dokumen baru dari nol (heading, paragraph, table, image)  
- 📤 **Export Multi-format** – simpan ke DOCX, PDF, ODT, HTML, atau multi-export sekaligus  
- ⚡ **DX-Friendly** – Facade `Word::`, helper `word()`, chainable API, fake mode untuk testing  
- 🧩 **Advanced** – merge beberapa `.docx`, watermark, tanda tangan digital  
- ⏰ **Automation** – Template Hub (registry + caching + scheduler)  

---

## 📦 Instalasi

```bash
composer require novay/laravel-word-template
````

Lalu publish config (opsional):

```bash
php artisan vendor:publish --tag=config --provider="novay\DocxGenerator\WordServiceProvider"
```

---

## ⚙️ Konfigurasi (`config/word.php`)

```php
return [
    'default_export' => 'docx',

    'export_profiles' => [
        'default' => ['docx', 'pdf'],
        'pdf_only' => ['pdf'],
        'all' => ['docx', 'pdf', 'odt', 'html'],
    ],

    'templates_path' => storage_path('app/word-templates'),

    'pdf_engine' => 'dompdf', // dompdf | tcpdf | mpdf
];
```

---

## 🚀 Pemakaian

### 1. Dari Template

```php
use Word;

$data = [
    'nama' => 'Budi',
    'alamat' => 'Jakarta',
    'barang' => [
        ['no' => 1, 'nama_barang' => 'Beras', 'jumlah' => '10kg'],
        ['no' => 2, 'nama_barang' => 'Gula', 'jumlah' => '5kg'],
    ],
];

return Word::load('invoice.docx')
    ->bind($data)
    ->setImage('logo', public_path('logo.png'))
    ->download('pdf', 'invoice.pdf');
```

### 2. Builder Mode

```php
Word::create()
    ->title('Laporan Harian', 1)
    ->text('Tanggal: ' . now()->toDateString())
    ->table([
        ['Barang' => 'Beras', 'Jumlah' => '10kg'],
        ['Barang' => 'Gula', 'Jumlah' => '5kg'],
    ], ['Barang', 'Jumlah'])
    ->setFooter('Confidential', true)
    ->exportProfile('default', 'laporan');
```

### 3. Merge Beberapa Dokumen

```php
Word::merge([
    storage_path('laporan_jan.docx'),
    storage_path('laporan_feb.docx'),
], storage_path('laporan_gabungan.docx'));
```

### 4. Tambah Watermark

```php
Word::load('template.docx')
    ->setWatermark(public_path('logo.png'))
    ->download();
```

### 5. Fake Mode untuk Testing

```php
Word::fake()->load('template.docx')->setValue('nama', 'Testing')->saveAs();
// Tidak membuat file, hanya return string path fake
```

---

## 📚 Cheat Sheet

| Method                              | Deskripsi                                        |
| ----------------------------------- | ------------------------------------------------ |
| `load($templatePath)`               | Buka template `.docx`                            |
| `fromTemplate($name)`               | Ambil template dari `storage/app/word-templates` |
| `setValue($key,$value)`             | Replace placeholder tunggal                      |
| `setValues(array $data)`            | Replace banyak placeholder                       |
| `bind(array $data)`                 | Auto binding (string → value, array → loop)      |
| `setLoop($key,$rows)`               | Loop sederhana                                   |
| `setNestedLoop($key,$rows)`         | Nested loop (`cloneBlock`)                       |
| `setImage($key,$path,$options=[])`  | Replace gambar                                   |
| `create($options=[])`               | Buat dokumen baru                                |
| `addHeading()/title()`              | Tambah heading                                   |
| `addParagraph()/text()`             | Tambah paragraf                                  |
| `addTableFromArray()/table()`       | Tambah tabel                                     |
| `addImage($path,$options=[])`       | Tambah gambar                                    |
| `setHeader($text)`                  | Header dokumen                                   |
| `setFooter($text,$paginate=false)`  | Footer dokumen                                   |
| `saveAs($path)`                     | Simpan ke `.docx`                                |
| `export($format,$path)`             | Export ke format lain                            |
| `download($format,$filename)`       | Download response                                |
| `exportProfile($profile,$basename)` | Multi-format export                              |
| `fake()`                            | Fake mode (testing)                              |
| `merge($files,$output)`             | Merge beberapa `.docx`                           |
| `setWatermark($path)`               | Tambah watermark                                 |
| `signDocument($doc,$key,$out)`      | Tanda tangan digital                             |


| Method                               | Deskripsi                                                  | Contoh                                              |
| ------------------------------------ | ---------------------------------------------------------- | --------------------------------------------------- |
| `load($templatePath)`                | Buka template `.docx`                                      | `Word::load('template.docx')`                       |
| `fromTemplate($name)`                | Ambil template dari `storage/app/word-templates`           | `Word::fromTemplate('invoice')`                     |
| `setValue($key, $value)`             | Replace placeholder tunggal                                | `->setValue('nama', 'Budi')`                        |
| `setValues(array $data)`             | Replace banyak placeholder sekaligus                       | `->setValues(['nama'=>'Budi','alamat'=>'Jakarta'])` |
| `bind(array $data)`                  | Auto-binding data (string → `setValue`, array → `setLoop`) | `->bind($data)`                                     |
| `setLoop($key, array $rows)`         | Loop data sederhana (list/tabel)                           | `->setLoop('barang', $rows)`                        |
| `setNestedLoop($key, array $rows)`   | Loop dalam loop (`cloneBlock`)                             | `->setNestedLoop('orders', $orders)`                |
| `setImage($key, $path, $options=[])` | Replace placeholder dengan gambar                          | `->setImage('foto', 'logo.png', ['width'=>120])`    |


✍️ Builder Mode

| Method                                                            | Deskripsi                        | Contoh                                       |
| ----------------------------------------------------------------- | -------------------------------- | -------------------------------------------- |
| `create($options=[])`                                             | Buat dokumen baru                | `Word::create()`                             |
| `addSection($options=[])`                                         | Tambah section baru              | `->addSection(['orientation'=>'landscape'])` |
| `addHeading($text, $level=1)` / `title($text, $level=1)`          | Tambah heading/title             | `->title('Laporan',1)`                       |
| `addParagraph($text, $style=[], $paraStyle=[])` / `text($text)`   | Tambah paragraf                  | `->text('Isi laporan')`                      |
| `addTableFromArray($data, $headers=[])` / `table($data,$headers)` | Tambah tabel dari array          | `->table($rows,['Barang','Jumlah'])`         |
| `addImage($path, $options=[])`                                    | Tambah gambar ke dokumen         | `->addImage('foto.png',['width'=>200])`      |
| `setHeader($text)`                                                | Set header dokumen               | `->setHeader('Laporan Harian')`              |
| `setFooter($text,$withPagination=false)`                          | Set footer (opsional pagination) | `->setFooter('Confidential',true)`           |


📤 Export

| Method                                    | Deskripsi                                            | Contoh                                 |
| ----------------------------------------- | ---------------------------------------------------- | -------------------------------------- |
| `saveAs($path=null)`                      | Simpan ke file `.docx`                               | `->saveAs('laporan.docx')`             |
| `export($format='docx',$path=null)`       | Simpan ke format lain (`docx`, `pdf`, `odt`, `html`) | `->export('pdf','laporan.pdf')`        |
| `download($format='docx',$filename=null)` | Kirim response download ke browser                   | `->download('pdf','invoice.pdf')`      |
| `exportProfile($profile,$baseName=null)`  | Export multi-format sesuai config                    | `->exportProfile('default','laporan')` |

⚡ Utility & DX

| Method                                                    | Deskripsi                      | Contoh                                             |
| --------------------------------------------------------- | ------------------------------ | -------------------------------------------------- |
| `fake()`                                                  | Mode testing, tidak buat file  | `Word::fake()->load(...)->saveAs()`                |
| `merge(array $files,$outputPath=null)`                    | Gabungkan beberapa docx        | `Word::merge(['a.docx','b.docx'],'gabungan.docx')` |
| `setWatermark($path)`                                     | Tambah watermark (gambar/logo) | `->setWatermark('logo.png')`                       |
| `signDocument($docPath,$privateKeyPath,$outputPath=null)` | Tandatangan digital dokumen    | `->signDocument('doc.docx','key.pem')`             |
🛠️ Shortcut Alias

| Alias                      | Sama dengan           | Contoh                             |
| -------------------------- | --------------------- | ---------------------------------- |
| `text($str)`               | `addParagraph()`      | `->text('Isi laporan')`            |
| `title($str,$lvl=1)`       | `addHeading()`        | `->title('Judul',2)`               |
| `table($data,$headers=[])` | `addTableFromArray()` | `->table($rows,['Nama','Jumlah'])` |




---

## 📜 License
Laravel Word Template is licensed under the MIT license. Enjoy!